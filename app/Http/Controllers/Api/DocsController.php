<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocsController extends Controller
{
    /**
     * Swagger UI HTML view
     */
    public function index()
    {
        $specUrl = url('/api/docs/openapi.json');

        return response(<<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ho Thanh Thien Portfolio & CMS API Documentation</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui.css">
    <link rel="icon" type="image/png" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/favicon-32x32.png">
    <style>
        body { margin: 0; padding: 0; background: #fafafa; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; }
        .topbar-custom {
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            color: #ffffff;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .topbar-custom h1 { margin: 0; font-size: 1.25rem; font-weight: 700; letter-spacing: -0.02em; }
        .topbar-custom .meta { font-size: 0.85rem; opacity: 0.9; }
        .topbar-custom a { color: #ffffff; text-decoration: none; font-weight: 600; padding: 6px 12px; background: rgba(255,255,255,0.2); border-radius: 6px; }
        .topbar-custom a:hover { background: rgba(255,255,255,0.3); }
        #swagger-ui { max-width: 1400px; margin: 0 auto; padding-bottom: 40px; }
        .swagger-ui .topbar { display: none; }
    </style>
</head>
<body>
    <div class="topbar-custom">
        <div>
            <h1>Ho Thanh Thien — Portfolio & CMS API Docs</h1>
            <div class="meta">RESTful API v1 • Built with Laravel & PostgreSQL/MySQL • Deployed on Production</div>
        </div>
        <div style="display:flex; gap:10px; align-items:center;">
            <a href="{$specUrl}" target="_blank">OpenAPI JSON</a>
            <a href="/" target="_blank">View Website</a>
        </div>
    </div>
    <div id="swagger-ui"></div>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui-bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5.18.2/swagger-ui-standalone-preset.js"></script>
    <script>
        window.onload = function() {
            window.ui = SwaggerUIBundle({
                url: "{$specUrl}",
                dom_id: '#swagger-ui',
                deepLinking: true,
                presets: [
                    SwaggerUIBundle.presets.apis,
                    SwaggerUIStandalonePreset
                ],
                layout: "StandaloneLayout",
                persistAuthorization: true
            });
        };
    </script>
</body>
</html>
HTML, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    /**
     * OpenAPI 3.0 Specification JSON
     */
    public function openapi()
    {
        $baseUrl = url('');

        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Ho Thanh Thien Portfolio & CMS API',
                'description' => "Complete RESTful API for Ho Thanh Thien's Personal Portfolio & Content Management System.\n\nProvides endpoints for Projects, Blog Posts (with full CRUD), Skills, Experiences, Contact submissions, and Token-based Authentication.",
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'Ho Thanh Thien',
                    'email' => 'hothanhthien119@gmail.com',
                    'url' => 'https://hothanhthien.io.vn',
                ],
            ],
            'servers' => [
                [
                    'url' => $baseUrl,
                    'description' => 'Current Environment Server',
                ],
                [
                    'url' => 'https://hothanhthien.io.vn',
                    'description' => 'Production Server',
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'Sanctum Token',
                        'description' => 'Enter your Sanctum API Token obtained from /api/v1/auth/login',
                    ],
                ],
                'schemas' => [
                    'Project' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'title' => ['type' => 'string', 'example' => 'Personal Portfolio & CMS'],
                            'description' => ['type' => 'string', 'example' => 'Developed a personal portfolio and CMS with bilingual support.'],
                            'image_url' => ['type' => 'string', 'example' => 'https://hothanhthien.io.vn/images/og-image.webp'],
                            'github_url' => ['type' => 'string', 'example' => 'https://github.com/hoThanhThien/CV4.0'],
                            'demo_url' => ['type' => 'string', 'example' => 'https://hothanhthien.io.vn'],
                            'featured' => ['type' => 'boolean', 'example' => true],
                            'order' => ['type' => 'integer', 'example' => 1],
                            'technologies' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer', 'example' => 1],
                                        'name' => ['type' => 'string', 'example' => 'Laravel'],
                                        'color' => ['type' => 'string', 'example' => '#ff2d20'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'BlogPost' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'title' => ['type' => 'string', 'example' => 'Building High-Performance APIs with Laravel and Docker'],
                            'slug' => ['type' => 'string', 'example' => 'building-high-performance-apis-with-laravel-and-docker'],
                            'excerpt' => ['type' => 'string', 'example' => 'A comprehensive guide to containerizing and optimizing Laravel APIs.'],
                            'content' => ['type' => 'string', 'example' => 'Full article markdown or HTML content...'],
                            'published' => ['type' => 'boolean', 'example' => true],
                            'published_at' => ['type' => 'string', 'format' => 'date-time', 'example' => '2026-10-01T12:00:00Z'],
                        ],
                    ],
                    'SkillCategory' => [
                        'type' => 'object',
                        'properties' => [
                            'category' => ['type' => 'string', 'example' => 'Backend'],
                            'skills' => [
                                'type' => 'array',
                                'items' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer', 'example' => 1],
                                        'name' => ['type' => 'string', 'example' => 'PHP / Laravel'],
                                        'level' => ['type' => 'integer', 'example' => 85],
                                        'order' => ['type' => 'integer', 'example' => 1],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'Experience' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer', 'example' => 1],
                            'company' => ['type' => 'string', 'example' => 'Personal Portfolio Project'],
                            'position' => ['type' => 'string', 'example' => 'Full-Stack Developer'],
                            'description' => ['type' => 'string', 'example' => 'Designed architecture and CI/CD pipelines.'],
                            'start_date' => ['type' => 'string', 'format' => 'date', 'example' => '2025-06-01'],
                            'end_date' => ['type' => 'string', 'format' => 'date', 'nullable' => true, 'example' => '2025-10-31'],
                            'is_current' => ['type' => 'boolean', 'example' => false],
                            'order' => ['type' => 'integer', 'example' => 1],
                        ],
                    ],
                ],
            ],
            'paths' => [
                '/api/v1/auth/login' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Login to obtain Sanctum API Bearer Token',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['email', 'password'],
                                        'properties' => [
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'hothanhthien119@gmail.com'],
                                            'password' => ['type' => 'string', 'example' => 'password'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Login successful',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'token' => ['type' => 'string', 'example' => '1|abcdef123456...'],
                                                'token_type' => ['type' => 'string', 'example' => 'Bearer'],
                                                'user' => ['type' => 'object'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '401' => ['description' => 'Invalid email or password'],
                        ],
                    ],
                ],
                '/api/v1/auth/me' => [
                    'get' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Get current authenticated user profile',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'User profile details'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                ],
                '/api/v1/auth/logout' => [
                    'post' => [
                        'tags' => ['Authentication'],
                        'summary' => 'Revoke current API token',
                        'security' => [['bearerAuth' => []]],
                        'responses' => [
                            '200' => ['description' => 'Logged out successfully'],
                        ],
                    ],
                ],

                // Projects
                '/api/v1/projects' => [
                    'get' => [
                        'tags' => ['Projects'],
                        'summary' => 'List all portfolio projects',
                        'parameters' => [
                            ['name' => 'featured', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'boolean'], 'description' => 'Filter by featured status'],
                            ['name' => 'tech', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string'], 'description' => 'Filter by technology name (e.g. Laravel, ReactJS)'],
                            ['name' => 'search', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string'], 'description' => 'Search by title or description'],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'List of projects with attached technologies',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'total' => ['type' => 'integer', 'example' => 4],
                                                'data' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Project']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'post' => [
                        'tags' => ['Projects'],
                        'summary' => 'Create a new project (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['title', 'description'],
                                        'properties' => [
                                            'title' => ['type' => 'string', 'example' => 'E-Commerce Platform'],
                                            'description' => ['type' => 'string', 'example' => 'Microservice-based online shop with payment gateway integration.'],
                                            'github_url' => ['type' => 'string', 'example' => 'https://github.com/hoThanhThien/demo'],
                                            'demo_url' => ['type' => 'string', 'example' => 'https://demo.example.com'],
                                            'featured' => ['type' => 'boolean', 'example' => true],
                                            'order' => ['type' => 'integer', 'example' => 1],
                                            'technology_ids' => ['type' => 'array', 'items' => ['type' => 'integer'], 'example' => [1, 2]],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => ['description' => 'Project created successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                ],
                '/api/v1/projects/{id}' => [
                    'get' => [
                        'tags' => ['Projects'],
                        'summary' => 'Get project details by ID',
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer'], 'example' => 4],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Project details'],
                            '404' => ['description' => 'Project not found'],
                        ],
                    ],
                    'put' => [
                        'tags' => ['Projects'],
                        'summary' => 'Update a project (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'title' => ['type' => 'string'],
                                            'description' => ['type' => 'string'],
                                            'featured' => ['type' => 'boolean'],
                                            'technology_ids' => ['type' => 'array', 'items' => ['type' => 'integer']],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Project updated successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Projects'],
                        'summary' => 'Delete a project (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Project deleted successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                ],

                // Blog Posts
                '/api/v1/blog' => [
                    'get' => [
                        'tags' => ['Blog Posts'],
                        'summary' => 'List blog posts with pagination',
                        'parameters' => [
                            ['name' => 'search', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'string'], 'description' => 'Search title or content'],
                            ['name' => 'per_page', 'in' => 'query', 'required' => false, 'schema' => ['type' => 'integer', 'default' => 10]],
                        ],
                        'responses' => [
                            '200' => [
                                'description' => 'Paginated list of blog posts',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'data' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/BlogPost']],
                                                'pagination' => ['type' => 'object'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'post' => [
                        'tags' => ['Blog Posts'],
                        'summary' => 'Create a new blog post (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['title', 'content'],
                                        'properties' => [
                                            'title' => ['type' => 'string', 'example' => 'Optimizing Database Queries in Large Applications'],
                                            'slug' => ['type' => 'string', 'example' => 'optimizing-database-queries-in-large-applications'],
                                            'excerpt' => ['type' => 'string', 'example' => 'Tips on indexing, eager loading, and query profiling.'],
                                            'content' => ['type' => 'string', 'example' => '# Optimizing Database Queries\n\nHere are practical tips...'],
                                            'published' => ['type' => 'boolean', 'example' => true],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => ['description' => 'Blog post created successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                ],
                '/api/v1/blog/{slug}' => [
                    'get' => [
                        'tags' => ['Blog Posts'],
                        'summary' => 'Get blog post details by slug or ID',
                        'parameters' => [
                            ['name' => 'slug', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'string'], 'description' => 'Post slug or ID'],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Blog post details'],
                            '404' => ['description' => 'Blog post not found'],
                        ],
                    ],
                ],
                '/api/v1/blog/{id}' => [
                    'put' => [
                        'tags' => ['Blog Posts'],
                        'summary' => 'Update a blog post (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'requestBody' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'title' => ['type' => 'string'],
                                            'excerpt' => ['type' => 'string'],
                                            'content' => ['type' => 'string'],
                                            'published' => ['type' => 'boolean'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Blog post updated successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                    'delete' => [
                        'tags' => ['Blog Posts'],
                        'summary' => 'Delete a blog post (Admin Only)',
                        'security' => [['bearerAuth' => []]],
                        'parameters' => [
                            ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                        ],
                        'responses' => [
                            '200' => ['description' => 'Blog post deleted successfully'],
                            '401' => ['description' => 'Unauthenticated'],
                        ],
                    ],
                ],

                // Skills & Experiences
                '/api/v1/skills' => [
                    'get' => [
                        'tags' => ['Skills & Experiences'],
                        'summary' => 'Get all technical skills grouped by category',
                        'responses' => [
                            '200' => [
                                'description' => 'List of skills by category',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'total' => ['type' => 'integer', 'example' => 15],
                                                'categories' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/SkillCategory']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                '/api/v1/experiences' => [
                    'get' => [
                        'tags' => ['Skills & Experiences'],
                        'summary' => 'Get all work and project experiences',
                        'responses' => [
                            '200' => [
                                'description' => 'List of experiences',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'total' => ['type' => 'integer', 'example' => 2],
                                                'data' => ['type' => 'array', 'items' => ['$ref' => '#/components/schemas/Experience']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                // Contact
                '/api/v1/contact' => [
                    'post' => [
                        'tags' => ['Contact'],
                        'summary' => 'Submit a contact message via API',
                        'requestBody' => [
                            'required' => true,
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'required' => ['name', 'email', 'message'],
                                        'properties' => [
                                            'name' => ['type' => 'string', 'example' => 'John Doe'],
                                            'email' => ['type' => 'string', 'format' => 'email', 'example' => 'john@example.com'],
                                            'subject' => ['type' => 'string', 'example' => 'Backend Engineering Opportunity'],
                                            'message' => ['type' => 'string', 'minLength' => 10, 'example' => 'Hello Thien, I reviewed your CV and projects and would love to discuss an engineering role with our team.'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'responses' => [
                            '201' => [
                                'description' => 'Contact message saved successfully',
                                'content' => [
                                    'application/json' => [
                                        'schema' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'success' => ['type' => 'boolean', 'example' => true],
                                                'message' => ['type' => 'string', 'example' => 'Thank you for reaching out! Your message has been received.'],
                                                'data' => ['type' => 'object'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            '422' => ['description' => 'Validation error'],
                        ],
                    ],
                ],
            ],
        ];

        return response()->json($spec, 200, ['Content-Type' => 'application/json; charset=UTF-8'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }
}
