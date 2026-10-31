<?php

namespace Database\Seeders;

use App\Models\Certificate;
use Illuminate\Database\Seeder;

class CertificateSeeder extends Seeder
{
    public function run(): void
    {
        $storageDir = storage_path('app/public/certificates');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        $targetFile = $storageDir . '/HoThanhThien_CV_Brochure.pdf';
        $fallbackSource = public_path('assets/docs/HoThanhThien_CV_Brochure.pdf');
        if (!file_exists($targetFile) && file_exists($fallbackSource)) {
            copy($fallbackSource, $targetFile);
        }

        Certificate::truncate();

        Certificate::create([
            'title' => 'Brochure & Hồ sơ Năng lực Cá nhân (Portfolio Brochure)',
            'category' => 'brochure',
            'issuer' => 'Hồ Thanh Thiện',
            'issue_date' => '2026',
            'description' => "Bản Brochure tổng hợp toàn diện hồ sơ năng lực cá nhân, kinh nghiệm làm việc thực chiến, các dự án tiêu biểu và kỹ năng chuyên sâu của Kỹ sư Hồ Thanh Thiện.",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 0,
        ]);

        Certificate::create([
            'title' => 'Bằng Kỹ sư / Cử nhân Công nghệ Thông tin',
            'category' => 'degree',
            'issuer' => 'Trường Đại học Công nghiệp Hà Nội (HaUI)',
            'issue_date' => '2020 - 2024',
            'description' => "Tốt nghiệp chuyên ngành Công nghệ Thông tin, hoàn thành chương trình đào tạo với các đồ án ứng dụng web, trí tuệ nhân tạo và hệ thống doanh nghiệp.",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 1,
        ]);

        Certificate::create([
            'title' => 'Giải Nhất Cuộc thi Sáng tác Logo & Nhận diện Khoa CNTT',
            'category' => 'award',
            'issuer' => 'Khoa Công nghệ Thông tin - HaUI',
            'issue_date' => '2022',
            'description' => "Đạt giải Quán quân trong cuộc thi thiết kế sáng tạo biểu trưng chính thức cho Khoa Công nghệ Thông tin - Trường Đại học Công nghiệp Hà Nội.",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 2,
        ]);
    }
}
