<?php

namespace App\Models;

use App\Models\Concerns\HasBilingualTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;
    use HasBilingualTranslations {
        localized as protected traitLocalized;
    }

    /**
     * Legacy seeded Vietnamese defaults mapped to English when explicit EN translations are not yet persisted.
     */
    private const DEFAULT_EN_FALLBACKS = [
        'title' => [
            'Brochure & Hồ sơ Năng lực Cá nhân (Portfolio Brochure)' => 'Portfolio & Competency Brochure (CV PDF)',
            'Bằng Cử nhân Công nghệ Thông tin' => 'B.Eng in Information Technology',
            'Giải Nhì Cuộc thi Sáng tác Logo & Nhận diện Khoa/Viện CNTT' => '2nd Prize — IT Faculty Logo & Visual Identity Contest',
        ],
        'issuer' => [
            'Hồ Thành Thiện' => 'Ho Thanh Thien',
            'Trường ĐH Giao thông Vận tải TP.HCM (UTH)' => 'University of Transport Ho Chi Minh City (UTH)',
            'Khoa/Viện Công nghệ Thông tin - UTH' => 'Faculty of Information Technology — UTH',
        ],
        'description' => [
            'Bản Brochure tổng hợp toàn diện hồ sơ năng lực cá nhân, kinh nghiệm làm việc thực chiến, các dự án tiêu biểu và kỹ năng chuyên sâu của Kỹ sư Hồ Thành Thiện.' => 'Comprehensive portfolio brochure summarizing engineering competencies, practical work experience, flagship projects, and technical stack of Software Engineer Ho Thanh Thien.',
            'Tốt nghiệp Cử nhân chuyên ngành Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc (GPA kỳ gần nhất 4.0/4.0).' => 'Bachelor of Engineering in Information Technology graduate at University of Transport Ho Chi Minh City (UTH) with Distinction honors (latest semester GPA 4.0/4.0).',
            'Đạt Giải Nhì trong cuộc thi thiết kế sáng tạo biểu trưng chính thức cho Khoa/Viện Công nghệ Thông tin - Trường ĐH Giao thông Vận tải TP.HCM (UTH).' => 'Awarded 2nd Prize in the official logo and visual identity design contest for the Faculty of Information Technology, University of Transport Ho Chi Minh City (UTH).',
        ],
    ];

    public function localized(string $field, ?string $locale = null): mixed
    {
        $targetLocale = $locale ?: app()->getLocale();
        $translated = $this->translationValue($field, $targetLocale);
        if ($translated !== null) {
            return $translated;
        }

        $base = $this->traitLocalized($field, $locale);
        if ($targetLocale === 'en' && is_string($base) && isset(self::DEFAULT_EN_FALLBACKS[$field][$base])) {
            return self::DEFAULT_EN_FALLBACKS[$field][$base];
        }

        return $base;
    }

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
        'translations',
    ];

    public function getLocalizedIssuerAttribute(): string
    {
        return (string) $this->localized('issuer');
    }

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
