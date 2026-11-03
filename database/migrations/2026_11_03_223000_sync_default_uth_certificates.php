<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('certificates')) {
            return;
        }

        // Fix typo in default brochure issuer name if present
        DB::table('certificates')
            ->where('issuer', 'Hồ Thanh Thiện')
            ->update([
                'issuer' => 'Hồ Thành Thiện',
                'description' => 'Bản Brochure tổng hợp toàn diện hồ sơ năng lực cá nhân, kinh nghiệm làm việc thực chiến, các dự án tiêu biểu và kỹ năng chuyên sâu của Kỹ sư Hồ Thành Thiện.',
            ]);

        // Upgrade legacy HaUI placeholder degree record to UTH if untouched
        DB::table('certificates')
            ->where('category', 'degree')
            ->where('issuer', 'like', '%HaUI%')
            ->update([
                'title' => 'Bằng Cử nhân Công nghệ Thông tin',
                'issuer' => 'Trường ĐH Giao thông Vận tải TP.HCM (UTH)',
                'issue_date' => '2026',
                'description' => 'Tốt nghiệp Cử nhân chuyên ngành Công nghệ Thông tin tại Trường ĐH Giao thông Vận tải TP.HCM (UTH) với xếp loại Tốt nghiệp Xuất sắc (GPA kỳ gần nhất 4.0/4.0).',
            ]);

        // Upgrade legacy HaUI placeholder award record to UTH if untouched
        DB::table('certificates')
            ->where('category', 'award')
            ->where('issuer', 'like', '%HaUI%')
            ->update([
                'title' => 'Giải Nhì Cuộc thi Sáng tác Logo & Nhận diện Khoa/Viện CNTT',
                'issuer' => 'Khoa/Viện Công nghệ Thông tin - UTH',
                'issue_date' => '2025',
                'description' => 'Đạt Giải Nhì trong cuộc thi thiết kế sáng tạo biểu trưng chính thức cho Khoa/Viện Công nghệ Thông tin - Trường ĐH Giao thông Vận tải TP.HCM (UTH).',
            ]);
    }

    public function down()
    {
        // Non-destructive data sync
    }
};

