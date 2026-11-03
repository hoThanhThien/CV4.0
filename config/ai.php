<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AI Blog Studio Configuration
    |--------------------------------------------------------------------------
    |
    | Centralized, environment-driven configuration for AI providers and
    | content generation. Provider credentials, models, and custom base URLs
    | are managed dynamically in the `ai_providers` database table via Admin.
    |
    */

    'author_name' => env('AI_BLOG_AUTHOR_NAME', env('ADMIN_NAME', env('APP_NAME', 'Tech Blog'))),
    'site_url' => env('APP_URL', ''),

    'providers' => [
        'gemini' => [
            'base_url' => env('GEMINI_BASE_URL', 'https://generativelanguage.googleapis.com/v1beta'),
            'vertex_express_base_url' => env('GEMINI_VERTEX_BASE_URL', 'https://aiplatform.googleapis.com/v1/publishers/google'),
            'use_interactions_api' => (bool) env('GEMINI_USE_INTERACTIONS_API', true),
            'models_cache_ttl' => (int) env('GEMINI_MODELS_CACHE_TTL', 1800),
            'min_blog_output_tokens' => (int) env('GEMINI_MIN_BLOG_OUTPUT_TOKENS', 16384),
        ],

        'openai' => [
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        ],

        'deepseek' => [
            'base_url' => env('DEEPSEEK_BASE_URL', 'https://api.deepseek.com'),
        ],
    ],

];
