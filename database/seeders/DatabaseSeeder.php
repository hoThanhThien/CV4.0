<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;
use App\Models\Experience;
use App\Models\Technology;
use App\Models\Project;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // ====== Admin User ======
        $adminEmail = env('ADMIN_EMAIL');
        $adminPassword = env('ADMIN_PASSWORD');
        $adminName = env('ADMIN_NAME', 'Admin');

        if (!empty($adminEmail) && !empty($adminPassword)) {
            User::firstOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make($adminPassword),
                ]
            );
        }

        // ====== Skills (From PDF) ======
        $skills = [
            // Languages
            [
                'name' => 'Java',
                'details' => 'OOP, Collections, Exception Handling, Multithreading',
                'level' => 88,
                'category' => 'Languages',
                'icon' => 'fab fa-java',
                'order' => 1,
            ],
            [
                'name' => 'Python',
                'details' => 'OOP, Async Programming, Scripting',
                'level' => 88,
                'category' => 'Languages',
                'icon' => 'fab fa-python',
                'order' => 2,
            ],
            [
                'name' => 'PHP',
                'details' => 'MVC Architecture, Composer',
                'level' => 88,
                'category' => 'Languages',
                'icon' => 'fab fa-php',
                'order' => 3,
            ],
            [
                'name' => 'JavaScript (ES6+)',
                'details' => 'DOM Manipulation, Event Handling, Async/Await, Fetch API',
                'level' => 88,
                'category' => 'Languages',
                'icon' => 'fab fa-js',
                'order' => 4,
            ],
            [
                'name' => 'SQL',
                'details' => 'DDL, DML, Joins, Stored Procedures, Indexing',
                'level' => 86,
                'category' => 'Languages',
                'icon' => 'fas fa-database',
                'order' => 5,
            ],

            // Frontend
            [
                'name' => 'ReactJS',
                'details' => 'Hooks, JSX, Functional Components, React Router, Context API, React Query, State Management, Component Reusability, Responsive Design',
                'level' => 90,
                'category' => 'Frontend',
                'icon' => 'fab fa-react',
                'order' => 1,
            ],
            [
                'name' => 'HTML5',
                'details' => 'Semantic HTML',
                'level' => 92,
                'category' => 'Frontend',
                'icon' => 'fab fa-html5',
                'order' => 2,
            ],
            [
                'name' => 'CSS3',
                'details' => 'Flexbox, Grid, Animations',
                'level' => 90,
                'category' => 'Frontend',
                'icon' => 'fab fa-css3-alt',
                'order' => 3,
            ],
            [
                'name' => 'Tailwind CSS',
                'details' => 'Responsive Design, Utility Classes, Custom Design Systems',
                'level' => 85,
                'category' => 'Frontend',
                'icon' => 'fas fa-wind',
                'order' => 4,
            ],
            [
                'name' => 'Bootstrap',
                'details' => 'Responsive Grid, UI Components, Fast Prototyping',
                'level' => 85,
                'category' => 'Frontend',
                'icon' => 'fab fa-bootstrap',
                'order' => 5,
            ],

            // Backend
            [
                'name' => 'Spring Boot',
                'details' => 'Spring Web, Spring Data JPA, Spring Security, Maven, Dependency Injection, RESTful APIs',
                'level' => 86,
                'category' => 'Backend',
                'icon' => 'fas fa-leaf',
                'order' => 1,
            ],
            [
                'name' => 'FastAPI',
                'details' => 'Async API Development, Pydantic, Dependency Injection, WebSocket',
                'level' => 86,
                'category' => 'Backend',
                'icon' => 'fas fa-bolt',
                'order' => 2,
            ],
            [
                'name' => 'Django',
                'details' => 'MVT Architecture, ORM, Admin Site, Authentication',
                'level' => 80,
                'category' => 'Backend',
                'icon' => 'fab fa-python',
                'order' => 3,
            ],
            [
                'name' => 'PHP (Backend)',
                'details' => 'MVC, CRUD, Session, Authentication',
                'level' => 86,
                'category' => 'Backend',
                'icon' => 'fab fa-php',
                'order' => 4,
            ],
            [
                'name' => 'Laravel',
                'details' => 'Eloquent ORM, Blade, Routing, Middleware, Artisan, MVC',
                'level' => 88,
                'category' => 'Backend',
                'icon' => 'fab fa-laravel',
                'order' => 5,
            ],

            // Database
            [
                'name' => 'MySQL',
                'details' => 'Database Design, Normalization, Joins, Transactions, Indexing',
                'level' => 88,
                'category' => 'Database',
                'icon' => 'fas fa-database',
                'order' => 1,
            ],
            [
                'name' => 'PostgreSQL',
                'details' => 'Relational Modeling, Indexing, Performance Optimization',
                'level' => 82,
                'category' => 'Database',
                'icon' => 'fas fa-database',
                'order' => 2,
            ],
            [
                'name' => 'SQL Server',
                'details' => 'T-SQL, Stored Procedures, Relational Database Management',
                'level' => 78,
                'category' => 'Database',
                'icon' => 'fas fa-server',
                'order' => 3,
            ],

            // API Development
            [
                'name' => 'RESTful API Design',
                'details' => 'CRUD Operations, JSON, API Documentation (Swagger/OpenAPI), Pagination, Filtering, Error Handling',
                'level' => 90,
                'category' => 'API Development',
                'icon' => 'fas fa-network-wired',
                'order' => 1,
            ],
            [
                'name' => 'GraphQL',
                'details' => 'Schemas, Queries, Mutations, Resolvers',
                'level' => 78,
                'category' => 'API Development',
                'icon' => 'fas fa-project-diagram',
                'order' => 2,
            ],

            // Software Engineering
            [
                'name' => 'Agile / Scrum',
                'details' => 'Sprint Planning, Daily Standups, Iterative Workflow',
                'level' => 88,
                'category' => 'Software Engineering',
                'icon' => 'fas fa-tasks',
                'order' => 1,
            ],
            [
                'name' => 'CI/CD Pipelines',
                'details' => 'Automated Deployment, Code Integration, Build Pipelines',
                'level' => 82,
                'category' => 'Software Engineering',
                'icon' => 'fas fa-sync-alt',
                'order' => 2,
            ],
            [
                'name' => 'Clean Code & Documentation',
                'details' => 'Readable Code, Comprehensive Documentation, OpenAPI/Swagger',
                'level' => 90,
                'category' => 'Software Engineering',
                'icon' => 'fas fa-file-code',
                'order' => 3,
            ],
            [
                'name' => 'MVC & SOLID Principles',
                'details' => 'Single Responsibility, Open/Closed, Clean Architecture',
                'level' => 88,
                'category' => 'Software Engineering',
                'icon' => 'fas fa-cubes',
                'order' => 4,
            ],
            [
                'name' => 'Design Patterns',
                'details' => 'Factory, Singleton, Repository Pattern',
                'level' => 85,
                'category' => 'Software Engineering',
                'icon' => 'fas fa-sitemap',
                'order' => 5,
            ],

            // Authentication
            [
                'name' => 'JWT Authentication',
                'details' => 'Stateless Token Auth, Refresh Tokens, Token Validation',
                'level' => 88,
                'category' => 'Authentication',
                'icon' => 'fas fa-key',
                'order' => 1,
            ],
            [
                'name' => 'OTP Verification',
                'details' => 'Time-based & Counter-based One Time Passwords, Email / SMS OTP',
                'level' => 85,
                'category' => 'Authentication',
                'icon' => 'fas fa-shield-alt',
                'order' => 2,
            ],
            [
                'name' => 'Role-Based Access Control (RBAC)',
                'details' => 'Permissions, Role Hierarchies, Middleware & Route Guards',
                'level' => 88,
                'category' => 'Authentication',
                'icon' => 'fas fa-user-shield',
                'order' => 3,
            ],
            [
                'name' => 'Session Management',
                'details' => 'Secure Cookies, Session Lifecycles, Multi-Device Sessions',
                'level' => 85,
                'category' => 'Authentication',
                'icon' => 'fas fa-id-badge',
                'order' => 4,
            ],
            [
                'name' => 'Password Hashing',
                'details' => 'Bcrypt, Argon2, Salt Hashing, Secure Password Storage',
                'level' => 88,
                'category' => 'Authentication',
                'icon' => 'fas fa-lock',
                'order' => 5,
            ],

            // Tools & Practices
            [
                'name' => 'Git & GitHub',
                'details' => 'Branching, Merge, Pull Request, Version Control',
                'level' => 90,
                'category' => 'Tools & Practices',
                'icon' => 'fab fa-git-alt',
                'order' => 1,
            ],
            [
                'name' => 'Docker & Docker Compose',
                'details' => 'Containerization, Multi-Container Orchestration, Environment Isolation',
                'level' => 82,
                'category' => 'Tools & Practices',
                'icon' => 'fab fa-docker',
                'order' => 2,
            ],
            [
                'name' => 'Firebase',
                'details' => 'Firestore, Realtime Database, Cloud Functions, Hosting',
                'level' => 80,
                'category' => 'Tools & Practices',
                'icon' => 'fas fa-fire',
                'order' => 3,
            ],
            [
                'name' => 'AWS (EC2 / S3)',
                'details' => 'Cloud Compute (EC2), Object Storage (S3), IAM Permissions',
                'level' => 75,
                'category' => 'Tools & Practices',
                'icon' => 'fab fa-aws',
                'order' => 4,
            ],
            [
                'name' => 'WordPress',
                'details' => 'CMS Customization, Theme Development, Plugin Integration',
                'level' => 85,
                'category' => 'Tools & Practices',
                'icon' => 'fab fa-wordpress',
                'order' => 5,
            ],
            [
                'name' => 'Postman',
                'details' => 'API Testing, Automated Test Suites, Mock Servers',
                'level' => 88,
                'category' => 'Tools & Practices',
                'icon' => 'fas fa-vial',
                'order' => 6,
            ],

            // Other
            [
                'name' => 'WebSocket / Socket.IO',
                'details' => 'Bi-directional Real-time Communication, Event Streaming',
                'level' => 85,
                'category' => 'Other',
                'icon' => 'fas fa-bolt',
                'order' => 1,
            ],
            [
                'name' => 'Responsive Web Design',
                'details' => 'Mobile-First Architecture, Fluid Grids, Cross-Browser Compatibility',
                'level' => 92,
                'category' => 'Other',
                'icon' => 'fas fa-mobile-alt',
                'order' => 2,
            ],
            [
                'name' => 'Payment Gateway Integration',
                'details' => 'MoMo, PayPal API Integration, IPN Callbacks',
                'level' => 84,
                'category' => 'Other',
                'icon' => 'fas fa-credit-card',
                'order' => 3,
            ],
            [
                'name' => 'Firebase Authentication',
                'details' => 'Email/Password, Social Auth, OAuth2 Provider Flow',
                'level' => 85,
                'category' => 'Other',
                'icon' => 'fas fa-user-lock',
                'order' => 4,
            ],
        ];

        // Clear and rebuild skills to match exact PDF specification
        Skill::truncate();
        foreach ($skills as $skill) {
            Skill::create($skill);
        }

        // ====== Experiences (From PDF) ======
        $experiences = [
            [
                'company' => 'Enmasys Enterprise Internship Semester',
                'position' => 'Odoo Developer Intern',
                'description' => 'Analyzed Odoo data models and business workflows to understand system requirements and implementation needs. Implemented and configured Odoo modules for Retail Management, Attendance Management, and Accounting. Worked on B2B Sales Operations, supporting business workflows and sales process implementation. Analyzed business requirements and mapped operational processes to Odoo models to support ERP customization and integration.',
                'start_date' => '2026-03-01',
                'end_date' => '2026-07-31',
                'current' => false,
                'order' => 1,
            ],
            [
                'company' => 'AIPower',
                'position' => 'Fullstack Developer',
                'description' => 'Developed and maintained educational websites using WordPress, focusing on responsive design and user experience. Built and customized corporate websites using PHP, integrating frontend interfaces with backend functionality. Worked with HTML, CSS, JavaScript, PHP, WordPress, and PostgreSQL to develop and maintain web applications. Collaborated with the development team to enhance website functionality, performance, and usability.',
                'start_date' => '2026-06-01',
                'end_date' => '2026-10-31',
                'current' => false,
                'order' => 2,
            ],
        ];

        Experience::truncate();
        foreach ($experiences as $exp) {
            Experience::create($exp);
        }

        // ====== Technologies ======
        $techs = [
            ['name' => 'PHP', 'color' => '#777bb4'],
            ['name' => 'Laravel', 'color' => '#ff2d20'],
            ['name' => 'Blade', 'color' => '#f05340'],
            ['name' => 'MySQL', 'color' => '#4479a1'],
            ['name' => 'Docker', 'color' => '#2496ed'],
            ['name' => 'Nginx', 'color' => '#009639'],
            ['name' => 'cPanel', 'color' => '#ff6c2c'],
            ['name' => 'Python', 'color' => '#3776ab'],
            ['name' => 'RAG', 'color' => '#8b5cf6'],
            ['name' => 'LLM', 'color' => '#06b6d4'],
            ['name' => 'Vector Search', 'color' => '#ec4899'],
            ['name' => 'FastAPI', 'color' => '#009688'],
            ['name' => 'Spring Boot', 'color' => '#6db33f'],
            ['name' => 'NextJS', 'color' => '#000000'],
            ['name' => 'ReactJS', 'color' => '#61dafb'],
            ['name' => 'RESTful API', 'color' => '#e11d48'],
            ['name' => 'JWT Authentication', 'color' => '#f59e0b'],
            ['name' => 'OTP', 'color' => '#10b981'],
            ['name' => 'WordPress', 'color' => '#21759b'],
            ['name' => 'PostgreSQL', 'color' => '#336791'],
            ['name' => 'HTML5', 'color' => '#e34f26'],
            ['name' => 'CSS3', 'color' => '#1572b6'],
            ['name' => 'JavaScript', 'color' => '#f7df1e'],
            ['name' => 'Odoo', 'color' => '#714b67'],
        ];

        $techModels = [];
        foreach ($techs as $tech) {
            $techModels[$tech['name']] = Technology::firstOrCreate(['name' => $tech['name']], $tech);
        }

        // ====== Projects (From PDF) ======
        $projects = [
            [
                'title' => 'Personal Portfolio & CMS',
                'description' => "Personal Portfolio & CMS | 08/2026 - 10/2026\nFullstack Developer\n+Project Description: Developed a personal portfolio and content management system with bilingual support (English/Vietnamese).\n+Built an admin dashboard for managing projects, skills, experiences, technologies, blog posts, and contact submissions.\n+Designed relational database schemas and implemented CRUD operations, authentication, and SEO features including dynamic sitemap generation.\n+Tech: PHP, Laravel, Blade, MySQL/SQLite, Docker, Nginx, cPanel\nDeploy: https://hothanhthien.io.vn/",
                'github_url' => 'https://github.com/hoThanhThien/CV4.0',
                'demo_url' => 'https://hothanhthien.io.vn',
                'featured' => true,
                'order' => 1,
                'techs' => ['PHP', 'Laravel', 'Blade', 'MySQL', 'Docker', 'Nginx', 'cPanel'],
            ],
            [
                'title' => 'TourRist',
                'description' => "TourRist | 01/2026 - 06/2026\nFullstack Developer\n+Project Description: Developed a document-based RAG chatbot that combines semantic retrieval with LLM generation to provide context-aware answers from a private knowledge base.\n+Implemented the document ingestion pipeline, including document loading, text preprocessing, chunking, and converting documents into embeddings for knowledge indexing.\n+Built the retrieval pipeline to perform semantic search and identify relevant document chunks based on the user's query.\n+Designed the RAG generation workflow to combine retrieved context with user queries and construct prompts for the LLM, reducing reliance on the model's internal knowledge.\n+Developed REST API endpoints for document ingestion, knowledge retrieval, and chatbot interactions.\n+Handled conversation context and response processing to provide relevant answers based on retrieved information.\n+Tech: Python, RAG, LLM, Embedding, Vector Search, REST API",
                'github_url' => 'https://github.com/hoThanhThien/TourRist',
                'demo_url' => null,
                'featured' => true,
                'order' => 2,
                'techs' => ['Python', 'RAG', 'LLM', 'Vector Search', 'FastAPI', 'RESTful API'],
            ],
            [
                'title' => 'Laundry Management System',
                'description' => "Laundry Management System | 01/2025 - 05/2025\nBack End\n+Project Description Developed a web-based laundry management system to manage orders, customers, services, and payment workflows.\n+Implemented order lifecycle management (received → washing → ready → completed) with real-time status updates.\n+Built role-based access control (RBAC) for admins and staff to ensure secure operations.\n+Optimized data fetching and API performance with pagination, caching, and proper error handling.\n+Tech: NextJS, Spring Boot, RESTful APIs, JWT Authentication, OTP\nhttps://github.com/hoThanhThien/LT_JAVA_010412213603",
                'github_url' => 'https://github.com/hoThanhThien/LT_JAVA_010412213603',
                'demo_url' => null,
                'featured' => true,
                'order' => 3,
                'techs' => ['NextJS', 'Spring Boot', 'RESTful API', 'JWT Authentication', 'MySQL'],
            ],
            [
                'title' => 'AIPower company',
                'description' => "AIPower company | 07/2026 - Present\nFullstack Developer\n+Developed Education websites using WordPress, focusing on responsive UI and user experience.\n+Built company websites using PHP, implementing frontend interfaces and integrating backend functionality.\n+Worked with HTML, CSS, JavaScript, PHP, WordPress, and Postgresql to develop and maintain web applications.\n+Collaborated with the development team to improve website functionality, performance, and usability.",
                'github_url' => null,
                'demo_url' => 'https://hothanhthien.io.vn/',
                'featured' => true,
                'order' => 4,
                'techs' => ['PHP', 'WordPress', 'PostgreSQL', 'JavaScript', 'HTML5', 'CSS3'],
            ],
        ];

        Project::truncate();
        \Illuminate\Support\Facades\DB::table('project_skill')->truncate();

        foreach ($projects as $projData) {
            $techNames = $projData['techs'];
            unset($projData['techs']);

            $project = Project::create($projData);

            // Connect skills to project
            $skillIds = [];
            foreach ($techNames as $techName) {
                $skill = Skill::where('name', $techName)->orWhere('name', 'LIKE', $techName . '%')->first();
                if (!$skill) {
                    $skill = Skill::create([
                        'name' => $techName,
                        'category' => 'Tools & Practices',
                        'level' => 85,
                        'order' => 20,
                        'icon' => 'fas fa-code',
                    ]);
                }
                $skillIds[] = $skill->id;
            }
            $project->skills()->sync(array_unique($skillIds));
        }

        // ====== Blog Posts ======
        BlogPost::truncate();
        $posts = [
            [
                'title' => 'Mastering React Hooks & State Management',
                'slug' => 'mastering-react-hooks',
                'excerpt' => 'Learn how to utilize React Hooks effectively for scalable state management and functional components.',
                'content' => "React Hooks have completely changed how we write React components. In this post, we'll dive deep into useState, useEffect, useCallback, and custom hooks to make your code cleaner, performant, and easily testable.",
                'published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Building Secure RESTful APIs with Spring Boot',
                'slug' => 'building-restful-apis-with-spring-boot',
                'excerpt' => 'A comprehensive guide to creating scalable, production-ready REST APIs using Java Spring Boot and JWT security.',
                'content' => "Spring Boot makes it incredibly fast to get enterprise-grade applications running. In this guide, we cover designing clean endpoints, configuring Spring Security with JWT tokens, handling exceptions centrally, and optimizing database queries with Spring Data JPA.",
                'published' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Building Document-Based RAG Systems with LLMs',
                'slug' => 'building-document-based-rag-systems',
                'excerpt' => 'How to design a retrieval-augmented generation pipeline using vector search, chunking, and modern LLMs.',
                'content' => "Retrieval-Augmented Generation (RAG) allows LLMs to accurately answer questions based on private knowledge bases. We will walk through the full architecture: document ingestion, embedding generation, vector indexing, semantic search, and context prompt injection.",
                'published' => true,
                'published_at' => now()->subDays(2),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::create($post);
        }
    }
}
