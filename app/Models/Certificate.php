<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'issuer',
        'issue_date',
        'description',
        'file_path',
        'file_type',
        'file_size',
        'extra_files',
        'thumbnail',
        'featured',
        'order',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'order' => 'integer',
        'extra_files' => 'array',
    ];

    public function getFileUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }
        return asset('storage/' . ltrim($this->file_path, '/'));
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            return null;
        }
        if (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://')) {
            return $this->thumbnail;
        }
        return asset('storage/' . ltrim($this->thumbnail, '/'));
    }

    public function getThumbnailIsPdfAttribute(): bool
    {
        if (!$this->thumbnail) {
            return false;
        }
        return str_ends_with(strtolower($this->thumbnail), '.pdf');
    }

    public function getExtraFilesWithUrlsAttribute(): array
    {
        if (empty($this->extra_files) || !is_array($this->extra_files)) {
            return [];
        }

        return array_map(function ($file) {
            $path = $file['path'] ?? '';
            $url = (str_starts_with($path, 'http://') || str_starts_with($path, 'https://'))
                ? $path
                : asset('storage/' . ltrim($path, '/'));

            return [
                'name' => $file['name'] ?? basename($path),
                'path' => $path,
                'url' => $url,
                'size' => $file['size'] ?? 'N/A',
                'type' => $file['type'] ?? (str_ends_with(strtolower($path), '.pdf') ? 'pdf' : 'image'),
                'is_pdf' => ($file['type'] ?? '') === 'pdf' || str_ends_with(strtolower($path), '.pdf'),
            ];
        }, $this->extra_files);
    }

    public function getIsPdfAttribute(): bool
    {
        return strtolower($this->file_type) === 'pdf' || str_ends_with(strtolower($this->file_path), '.pdf');
    }

    public function getCategoryLabelAttribute(): string
    {
        $labels = [
            'brochure' => app()->getLocale() == 'vi' ? 'Brochure / Hồ sơ' : 'Brochure / Portfolio',
            'certificate' => app()->getLocale() == 'vi' ? 'Giấy khen / Chứng chỉ' : 'Certificate',
            'degree' => app()->getLocale() == 'vi' ? 'Bằng cấp chính quy' : 'Degree / Diploma',
            'award' => app()->getLocale() == 'vi' ? 'Giải thưởng' : 'Award / Honor',
            'other' => app()->getLocale() == 'vi' ? 'Tài liệu' : 'Document',
        ];

        return $labels[$this->category] ?? ucfirst($this->category);
    }

    public function getCategoryIconAttribute(): string
    {
        return match ($this->category) {
            'brochure' => 'fas fa-book-open',
            'certificate' => 'fas fa-certificate',
            'degree' => 'fas fa-graduation-cap',
            'award' => 'fas fa-trophy',
            default => 'fas fa-file-pdf',
        };
    }
}
