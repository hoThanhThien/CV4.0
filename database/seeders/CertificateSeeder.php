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
            'issuer' => 'Hồ Thành Thiện',
            'issue_date' => '2026',
            'description' => "Bản Brochure tổng hợp toàn diện hồ sơ năng lực cá nhân, kinh nghiệm làm việc thực chiến, các dự án tiêu biểu và kỹ năng chuyên sâu của Kỹ sư Hồ Thành Thiện.",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 0,
            'translations' => [
                'en' => [
                    'title' => 'Portfolio & Competency Brochure (CV PDF)',
                    'issuer' => 'Ho Thanh Thien',
                    'description' => 'Comprehensive portfolio brochure summarizing engineering competencies, practical work experience, flagship projects, and technical stack of Software Engineer Ho Thanh Thien.',
                ],
            ],
        ]);

        Certificate::create([
            'title' => 'Bằng Cử nhân Công nghệ Thông tin',
            'category' => 'degree',
            'issuer' => 'Trường ĐH Giao thông Vận tải TP.HCM (UTH)',
            'issue_date' => '2026',
            'description' => "Tốt nghiệp Cử nhân chuyên ngành Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc (GPA kỳ gần nhất 4.0/4.0).",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 1,
            'translations' => [
                'en' => [
                    'title' => 'B.Eng in Information Technology',
                    'issuer' => 'University of Transport Ho Chi Minh City (UTH)',
                    'description' => 'Bachelor of Engineering in Information Technology graduate at University of Transport Ho Chi Minh City (UTH) with Distinction honors (latest semester GPA 4.0/4.0).',
                ],
            ],
        ]);

        Certificate::create([
            'title' => 'Giải Nhì Cuộc thi Sáng tác Logo & Nhận diện Khoa/Viện CNTT',
            'category' => 'award',
            'issuer' => 'Khoa/Viện Công nghệ Thông tin - UTH',
            'issue_date' => '2025',
            'description' => "Đạt Giải Nhì trong cuộc thi thiết kế sáng tạo biểu trưng chính thức cho Khoa/Viện Công nghệ Thông tin - Trường ĐH Giao thông Vận tải TP.HCM (UTH).",
            'file_path' => 'certificates/HoThanhThien_CV_Brochure.pdf',
            'file_type' => 'pdf',
            'file_size' => '402.15 KB',
            'thumbnail' => null,
            'featured' => true,
            'order' => 2,
            'translations' => [
                'en' => [
                    'title' => '2nd Prize — IT Faculty Logo & Visual Identity Contest',
                    'issuer' => 'Faculty of Information Technology — UTH',
                    'description' => 'Awarded 2nd Prize in the official logo and visual identity design contest for the Faculty of Information Technology, University of Transport Ho Chi Minh City (UTH).',
                ],
            ],
        ]);
    }
}
