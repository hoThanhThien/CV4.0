<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiProvider;
use App\Services\AI\AIProviderFactory;
use Illuminate\Http\Request;
use Throwable;

class AIProviderController extends Controller
{
    public function index()
    {
        $providers = AiProvider::latest()->get();
        return view('admin.ai.providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.ai.providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'provider_type' => 'required|in:openai,gemini,deepseek,custom',
            'api_key' => 'required|string',
            'model' => 'required|string|max:100',
            'base_url' => 'nullable|url|max:255',
            'temperature' => 'required|numeric|between:0,2',
            'max_tokens' => 'required|integer|min:200|max:65536',
            'timeout' => 'required|integer|min:10|max:300',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $validated = $this->cleanProviderData($validated);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        if ($validated['is_default']) {
            AiProvider::where('is_default', true)->update(['is_default' => false]);
        }

        AiProvider::create($validated);

        return redirect()->route('admin.ai.providers.index')->with('success', 'Đã thêm nhà cung cấp AI thành công!');
    }

    public function edit($id)
    {
        $provider = AiProvider::findOrFail($id);
        return view('admin.ai.providers.edit', compact('provider'));
    }

    public function update(Request $request, $id)
    {
        $provider = AiProvider::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'provider_type' => 'required|in:openai,gemini,deepseek,custom',
            'api_key' => 'nullable|string', // Leave blank to keep existing
            'model' => 'required|string|max:100',
            'base_url' => 'nullable|url|max:255',
            'temperature' => 'required|numeric|between:0,2',
            'max_tokens' => 'required|integer|min:200|max:65536',
            'timeout' => 'required|integer|min:10|max:300',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
        ]);

        $validated = $this->cleanProviderData($validated);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['is_default'] = $request->boolean('is_default', false);

        // Keep old API key if not updated
        if (empty($validated['api_key'])) {
            unset($validated['api_key']);
        }

        if ($validated['is_default']) {
            AiProvider::where('id', '!=', $id)->where('is_default', true)->update(['is_default' => false]);
        }

        $provider->update($validated);

        return redirect()->route('admin.ai.providers.index')->with('success', 'Đã cập nhật nhà cung cấp AI!');
    }

    private function cleanProviderData(array $validated): array
    {
        $validated['name'] = trim($validated['name']);
        if (!empty($validated['api_key'])) {
            $validated['api_key'] = trim($validated['api_key']);
        }
        if (!empty($validated['base_url'])) {
            $validated['base_url'] = trim($validated['base_url']);
        }

        $type = $validated['provider_type'];
        $model = trim($validated['model']);

        if ($type === 'gemini') {
            $model = (new \App\Services\AI\Providers\GeminiProvider())->normalizeModel($model);
        }

        $validated['model'] = $model;
        return $validated;
    }

    public function destroy($id)
    {
        $provider = AiProvider::findOrFail($id);
        $provider->delete();

        return redirect()->route('admin.ai.providers.index')->with('success', 'Đã xóa nhà cung cấp AI!');
    }

    public function testConnection($id)
    {
        $provider = AiProvider::findOrFail($id);

        try {
            $adapter = AIProviderFactory::make($provider);
            $result = $adapter->testConnection($provider);

            $provider->update([
                'last_tested_at' => now(),
                'connection_status' => $result['success'] ? 'connected' : 'failed',
                'last_error_message' => $result['success'] ? null : $result['message'],
            ]);

            if (request()->wantsJson()) {
                return response()->json($result);
            }

            if ($result['success']) {
                return back()->with('success', $result['message']);
            } else {
                return back()->with('error', $result['message']);
            }
        } catch (Throwable $e) {
            $provider->update([
                'last_tested_at' => now(),
                'connection_status' => 'failed',
                'last_error_message' => $e->getMessage(),
            ]);

            $msg = 'Lỗi kiểm tra kết nối: ' . $e->getMessage();
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }

            return back()->with('error', $msg);
        }
    }

    public function setDefault($id)
    {
        AiProvider::where('is_default', true)->update(['is_default' => false]);
        $provider = AiProvider::findOrFail($id);
        $provider->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', "Đã đặt '{$provider->name}' làm nhà cung cấp mặc định!");
    }

    public function toggleActive($id)
    {
        $provider = AiProvider::findOrFail($id);
        $provider->update(['is_active' => !$provider->is_active]);

        $status = $provider->is_active ? 'Đã kích hoạt' : 'Đã tạm dừng';
        return back()->with('success', "{$status} nhà cung cấp '{$provider->name}'!");
    }
}
