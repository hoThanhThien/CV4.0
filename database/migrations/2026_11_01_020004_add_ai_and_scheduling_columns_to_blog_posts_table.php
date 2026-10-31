<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('status', 50)->default('draft')->after('published_at'); // generating, draft, pending_review, scheduled, published, failed, cancelled
            $table->boolean('ai_generated')->default(false)->after('status');
            $table->foreignId('ai_provider_id')->nullable()->after('ai_generated')->constrained('ai_providers')->nullOnDelete();
            $table->foreignId('ai_config_id')->nullable()->after('ai_provider_id')->constrained('ai_content_configs')->nullOnDelete();
            $table->string('meta_title', 255)->nullable()->after('ai_config_id');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('keywords')->nullable()->after('meta_description');
            $table->string('category', 100)->nullable()->after('keywords');
            $table->text('tags')->nullable()->after('category');
            $table->text('faq')->nullable()->after('tags');
            $table->text('sources')->nullable()->after('faq');
            $table->text('cover_image_prompt')->nullable()->after('sources');
            $table->timestamp('scheduled_at')->nullable()->after('cover_image_prompt');
            $table->boolean('last_edited_by_admin')->default(false)->after('scheduled_at');
        });

        // Sync existing records: if published is true, set status = 'published'
        DB::table('blog_posts')->where('published', true)->update([
            'status' => 'published',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['ai_provider_id']);
            $table->dropForeign(['ai_config_id']);
            $table->dropColumn([
                'status',
                'ai_generated',
                'ai_provider_id',
                'ai_config_id',
                'meta_title',
                'meta_description',
                'keywords',
                'category',
                'tags',
                'faq',
                'sources',
                'cover_image_prompt',
                'scheduled_at',
                'last_edited_by_admin',
            ]);
        });
    }
};
