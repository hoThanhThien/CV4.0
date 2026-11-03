<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogAutomation;
use App\Models\AiContentConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Throwable;

class BlogAutomationController extends Controller
{
    public function index()
    {
        $automations = BlogAutomation::with(['contentConfig.provider'])->latest()->get();
        return view('admin.ai.automations.index', compact('automations'));
    }

    public function create()
    {
        $configs = AiContentConfig::active()->get();
        return view('admin.ai.automations.create', compact('configs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'content_config_id' => 'required|exists:ai_content_configs,id',
            'frequency' => 'required|in:daily,weekly,monthly',
            'execution_time' => 'required|date_format:H:i',
            'timezone' => 'required|string|max:50',
            'day_of_week' => 'nullable|integer|between:0,6',
            'day_of_month' => 'nullable|integer|between:1,31',
            'articles_per_run' => 'required|integer|min:1|max:5',
            'publishing_mode' => 'required|in:manual_approval,auto_schedule,auto_publish',
            'interval_minutes' => 'required|integer|min:15|max:1440',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['execution_time'] = $validated['execution_time'] . ':00';

        $automation = new BlogAutomation($validated);
        $automation->next_run_at = $automation->computeNextRunAt(now());
        $automation->save();

        return redirect()->route('admin.ai.automations.index')->with('success', 'Đã tạo lịch tự động hóa bài viết AI!');
    }

    public function edit($id)
    {
        $automation = BlogAutomation::findOrFail($id);
        $configs = AiContentConfig::active()->get();
        return view('admin.ai.automations.edit', compact('automation', 'configs'));
    }

    public function update(Request $request, $id)
    {
        $automation = BlogAutomation::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'content_config_id' => 'required|exists:ai_content_configs,id',
            'frequency' => 'required|in:daily,weekly,monthly',
            'execution_time' => 'required|date_format:H:i',
            'timezone' => 'required|string|max:50',
            'day_of_week' => 'nullable|integer|between:0,6',
            'day_of_month' => 'nullable|integer|between:1,31',
            'articles_per_run' => 'required|integer|min:1|max:5',
            'publishing_mode' => 'required|in:manual_approval,auto_schedule,auto_publish',
            'interval_minutes' => 'required|integer|min:15|max:1440',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['execution_time'] = $validated['execution_time'] . ':00';

        $automation->fill($validated);
        $automation->next_run_at = $automation->computeNextRunAt(now());
        $automation->save();

        return redirect()->route('admin.ai.automations.index')->with('success', 'Đã cập nhật lịch tự động hóa!');
    }

    public function destroy($id)
    {
        $automation = BlogAutomation::findOrFail($id);
        $automation->delete();

        return redirect()->route('admin.ai.automations.index')->with('success', 'Đã xóa lịch tự động hóa!');
    }

    public function toggleActive($id)
    {
        $automation = BlogAutomation::findOrFail($id);
        $automation->is_active = !$automation->is_active;
        if ($automation->is_active) {
            $automation->next_run_at = $automation->computeNextRunAt(now());
        }
        $automation->save();

        $msg = $automation->is_active ? 'Đã kích hoạt' : 'Đã tạm dừng';
        return back()->with('success', "{$msg} automation '{$automation->name}'!");
    }

    public function runNow($id)
    {
        @set_time_limit(300);

        $automation = BlogAutomation::with('contentConfig')->findOrFail($id);

        if (!$automation->contentConfig || !$automation->contentConfig->is_active) {
            return back()->with('error', "Cấu hình nội dung gắn với Automation '{$automation->name}' không tồn tại hoặc đang bị tắt!");
        }

        try {
            Artisan::call('blog:run-automations', [
                '--id' => $automation->id,
                '--force' => true,
            ]);

            $latestRun = \App\Models\BlogAutomationRun::where('automation_id', $automation->id)
                ->latest('id')
                ->first();

            if ($latestRun && $latestRun->status === 'failed') {
                return back()->with('error', "Automation '{$automation->name}' chạy thất bại: " . ($latestRun->error_details ?: 'Vui lòng kiểm tra Nhật ký hoạt động.'));
            }

            if ($latestRun && $latestRun->articles_created > 0) {
                return back()->with('success', "Đã chạy Automation '{$automation->name}' thành công: tạo mới {$latestRun->articles_created} bài viết! Kiểm tra tại mục AI Blog Posts.");
            }

            return back()->with('success', "Đã kích hoạt chạy Automation '{$automation->name}'. Vui lòng kiểm tra Nhật ký hoạt động hoặc Danh sách bài viết AI!");
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi khi kích hoạt: ' . $e->getMessage());
        }
    }

    public function triggerDueNow()
    {
        @set_time_limit(300);

        try {
            Artisan::call('blog:publish-scheduled');
            $pubOut = trim(Artisan::output());

            Artisan::call('blog:run-automations');
            $autoOut = trim(Artisan::output());

            return back()->with('success', 'Đã quét và xử lý toàn bộ lịch hẹn đến hạn! (' . ($pubOut ?: 'OK') . ' | ' . ($autoOut ?: 'OK') . ')');
        } catch (Throwable $e) {
            return back()->with('error', 'Lỗi khi quét lịch hẹn: ' . $e->getMessage());
        }
    }
}
