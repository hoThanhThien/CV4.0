<?php

use App\Support\PortfolioCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('certificates') || !Schema::hasColumn('certificates', 'translations')) {
            return;
        }

        $defaultsByTitle = [
            'Brochure & Hồ sơ Năng lực Cá nhân (Portfolio Brochure)' => [
                'title' => 'Portfolio & Competency Brochure (CV PDF)',
                'issuer' => 'Ho Thanh Thien',
                'description' => 'Comprehensive portfolio brochure summarizing engineering competencies, practical work experience, flagship projects, and technical stack of Software Engineer Ho Thanh Thien.',
            ],
            'Bằng Cử nhân Công nghệ Thông tin' => [
                'title' => 'B.Eng in Information Technology',
                'issuer' => 'University of Transport Ho Chi Minh City (UTH)',
                'description' => 'Bachelor of Engineering in Information Technology graduate at University of Transport Ho Chi Minh City (UTH) with Distinction honors (latest semester GPA 4.0/4.0).',
            ],
            'Giải Nhì Cuộc thi Sáng tác Logo & Nhận diện Khoa/Viện CNTT' => [
                'title' => '2nd Prize — IT Faculty Logo & Visual Identity Contest',
                'issuer' => 'Faculty of Information Technology — UTH',
                'description' => 'Awarded 2nd Prize in the official logo and visual identity design contest for the Faculty of Information Technology, University of Transport Ho Chi Minh City (UTH).',
            ],
        ];

        $rows = DB::table('certificates')->whereIn('title', array_keys($defaultsByTitle))->get();
        foreach ($rows as $row) {
            if (!isset($defaultsByTitle[$row->title])) {
                continue;
            }
            $translations = json_decode((string) ($row->translations ?? ''), true);
            if (!is_array($translations)) {
                $translations = [];
            }
            if (!empty($translations['en'])) {
                continue;
            }
            $translations['en'] = $defaultsByTitle[$row->title];
            DB::table('certificates')
                ->where('id', $row->id)
                ->update(['translations' => json_encode($translations, JSON_UNESCAPED_UNICODE)]);
        }

        if (class_exists(PortfolioCache::class)) {
            PortfolioCache::forgetAll();
        }
    }

    public function down(): void
    {
        // Non-destructive backfill
    }
};
