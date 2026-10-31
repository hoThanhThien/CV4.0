<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('issuer', 'like', "%{$s}%")
                  ->orWhere('description', 'like', "%{$s}%");
            });
        }

        $certificates = $query->orderBy('order')->orderByDesc('created_at')->paginate(12);

        return view('admin.certificates.index', compact('certificates'));
    }

    public function create()
    {
        return view('admin.certificates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:brochure,certificate,degree,award,other',
            'issuer' => 'nullable|string|max:255',
            'issue_date' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'thumbnail' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'extra_files' => 'nullable|array',
            'extra_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeBytes = $file->getSize();
            $fileSize = $this->formatBytes($sizeBytes);

            $storedPath = $file->store('certificates', 'public');
            $validated['file_path'] = $storedPath;
            $validated['file_type'] = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf';
            $validated['file_size'] = $fileSize;
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('certificates/thumbs', 'public');
        }

        // Handle multiple additional PDF / Document attachments
        $extraFiles = [];
        if ($request->hasFile('extra_files')) {
            foreach ($request->file('extra_files') as $extraFile) {
                $ext = strtolower($extraFile->getClientOriginalExtension());
                $storedExtra = $extraFile->store('certificates/extras', 'public');
                $extraFiles[] = [
                    'name' => $extraFile->getClientOriginalName(),
                    'path' => $storedExtra,
                    'size' => $this->formatBytes($extraFile->getSize()),
                    'type' => in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf',
                ];
            }
        }
        $validated['extra_files'] = $extraFiles;

        $validated['featured'] = $request->boolean('featured');
        $validated['order'] = (int) ($validated['order'] ?? 0);

        Certificate::create($validated);

        return redirect()->route('admin.certificates.index')->with('success', 'Tài liệu / Giấy khen đã được thêm thành công!');
    }

    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        return view('admin.certificates.edit', compact('certificate'));
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|in:brochure,certificate,degree,award,other',
            'issuer' => 'nullable|string|max:255',
            'issue_date' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'thumbnail' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'extra_files' => 'nullable|array',
            'extra_files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:25600',
            'remove_extra_files' => 'nullable|array',
            'featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        if ($request->hasFile('file')) {
            if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
                Storage::disk('public')->delete($certificate->file_path);
            }

            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());
            $sizeBytes = $file->getSize();
            $fileSize = $this->formatBytes($sizeBytes);

            $storedPath = $file->store('certificates', 'public');
            $validated['file_path'] = $storedPath;
            $validated['file_type'] = in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf';
            $validated['file_size'] = $fileSize;
        }

        if ($request->hasFile('thumbnail')) {
            if ($certificate->thumbnail && Storage::disk('public')->exists($certificate->thumbnail)) {
                Storage::disk('public')->delete($certificate->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('certificates/thumbs', 'public');
        }

        // Manage Extra Attached Files
        $currentExtras = $certificate->extra_files ?? [];

        // Remove files checked for deletion
        if ($request->filled('remove_extra_files')) {
            $removeList = (array) $request->input('remove_extra_files', []);
            $kept = [];
            foreach ($currentExtras as $idx => $item) {
                if (in_array((string) $idx, $removeList) || in_array($item['path'], $removeList)) {
                    if (!empty($item['path']) && Storage::disk('public')->exists($item['path'])) {
                        Storage::disk('public')->delete($item['path']);
                    }
                } else {
                    $kept[] = $item;
                }
            }
            $currentExtras = $kept;
        }

        // Add newly uploaded extra files
        if ($request->hasFile('extra_files')) {
            foreach ($request->file('extra_files') as $extraFile) {
                $ext = strtolower($extraFile->getClientOriginalExtension());
                $storedExtra = $extraFile->store('certificates/extras', 'public');
                $currentExtras[] = [
                    'name' => $extraFile->getClientOriginalName(),
                    'path' => $storedExtra,
                    'size' => $this->formatBytes($extraFile->getSize()),
                    'type' => in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf',
                ];
            }
        }
        $validated['extra_files'] = $currentExtras;

        $validated['featured'] = $request->boolean('featured');
        $validated['order'] = (int) ($validated['order'] ?? 0);

        $certificate->update($validated);

        return redirect()->route('admin.certificates.index')->with('success', 'Thông tin tài liệu / giấy khen đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);

        if ($certificate->file_path && Storage::disk('public')->exists($certificate->file_path)) {
            Storage::disk('public')->delete($certificate->file_path);
        }
        if ($certificate->thumbnail && Storage::disk('public')->exists($certificate->thumbnail)) {
            Storage::disk('public')->delete($certificate->thumbnail);
        }

        // Delete extra files from disk
        if (!empty($certificate->extra_files)) {
            foreach ($certificate->extra_files as $extra) {
                if (!empty($extra['path']) && Storage::disk('public')->exists($extra['path'])) {
                    Storage::disk('public')->delete($extra['path']);
                }
            }
        }

        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('success', 'Đã xóa tài liệu / giấy khen thành công!');
    }

    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
