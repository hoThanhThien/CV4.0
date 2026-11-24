<?php

use App\Models\Project;
use App\Models\Skill;
use App\Support\PortfolioCache;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('projects')) {
            return;
        }

        $targetDescription = "Tourest | 01/2026 - 06/2026\nFullstack Developer\n+Project Description: Tour Booking Management & Hybrid RAG AI platform built with React 18 (Vite), FastAPI, MySQL 8.0, and Redis 7.\n+Engineered Hybrid RAG chatbot (FAISS dense vector search + BM25 lexical search + RRF + Cross-Encoder reranking + Out-of-Scope LLM routing) with multi-turn context, Redis 2-tier caching & live Admin model tuning.\n+Implemented scikit-learn K-Means clustering (Customer, Tour & Destination segmentation with Elbow, Silhouette Score & PCA), 3-tier RBAC (Admin/Guide/User) and real-time WebSocket support chat.\n+Integrated multi-gateway online payments via PayPal SDK, MoMo (HMAC-SHA256 + IPN) and SePay bank webhook.\n+Tech: Python, FastAPI, ReactJS, RAG, FAISS, Vector Search, MySQL, Redis, scikit-learn, Docker";

        $translations = [
            'en' => [
                'title' => 'Tourest — Tour Booking & Hybrid RAG AI',
                'description' => $targetDescription,
            ],
            'vi' => [
                'title' => 'Tourest — Đặt Tour Du lịch & Hybrid RAG AI',
                'description' => "Tourest | 01/2026 - 06/2026\nLập trình viên Fullstack\n+Mô tả dự án: Hệ thống đặt tour du lịch trực tuyến & Chatbot Hybrid RAG AI phát triển trên React 18 (Vite), FastAPI, MySQL 8.0 và Redis 7.\n+Xây dựng pipeline Hybrid RAG (FAISS Dense Vector + BM25 Lexical + RRF Merge + Cross-Encoder Reranker + định tuyến câu hỏi ngoài danh mục) kèm cache 2 tầng Redis và trang Admin tùy chỉnh model trực tiếp.\n+Áp dụng K-Means Clustering (scikit-learn, Elbow, Silhouette, PCA) phân cụm khách hàng/điểm đến, phân quyền 3 cấp RBAC (Admin/Guide/User) và CSKH real-time qua WebSocket.\n+Tích hợp thanh toán đa cổng PayPal, MoMo (HMAC-SHA256 + IPN) và SePay Bank Webhook.\n+Công nghệ: Python, FastAPI, ReactJS, RAG, FAISS, Vector Search, MySQL, Redis, scikit-learn, Docker",
            ],
        ];

        $projects = Project::whereRaw('LOWER(title) LIKE ? OR LOWER(title) LIKE ?', ['%tourrist%', '%tourest%'])->get();
        foreach ($projects as $project) {
            $project->title = 'Tourest';
            $project->description = $targetDescription;
            $project->github_url = 'https://github.com/hoThanhThien/RAG';
            if (Schema::hasColumn('projects', 'translations')) {
                $existing = $project->translations;
                while (is_string($existing) && $existing !== '') {
                    $decoded = json_decode($existing, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $existing = [];
                        break;
                    }
                    $existing = $decoded;
                }
                if (!is_array($existing)) {
                    $existing = [];
                }
                $existing['en'] = $translations['en'];
                $existing['vi'] = $translations['vi'];
                $project->translations = $existing;
            }
            $project->save();

            if (Schema::hasTable('skills') && Schema::hasTable('project_skill')) {
                $skillIds = [];
                foreach (['Python', 'FastAPI', 'ReactJS', 'RAG', 'FAISS', 'Vector Search', 'MySQL', 'Redis', 'Docker'] as $techName) {
                    $skill = Skill::where('name', $techName)->orWhere('name', 'LIKE', $techName . '%')->first();
                    if (!$skill) {
                        $skill = Skill::create([
                            'name' => $techName,
                            'category' => in_array($techName, ['FAISS', 'Redis', 'Vector Search', 'MySQL'], true) ? 'Database' : 'Tools & Practices',
                            'level' => 86,
                            'order' => 20,
                            'icon' => 'fas fa-code',
                        ]);
                    }
                    $skillIds[] = $skill->id;
                }
                $project->skills()->sync(array_unique($skillIds));
            }
        }

        if (class_exists(PortfolioCache::class)) {
            PortfolioCache::forgetAll();
        }
    }

    public function down(): void
    {
        // Non-destructive sync
    }
};
