<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::firstOrCreate(
            ['email' => 'hothanhthien119@gmail.com'],
            [
                'name' => 'Hồ Thành Thiện',
                'password' => Hash::make('password'),
            ]
        );

        // ====== Skills ======
        $skills = [
            ['name' => 'ReactJS', 'level' => 85, 'category' => 'Frontend', 'order' => 1],
            ['name' => 'HTML5 / CSS3', 'level' => 90, 'category' => 'Frontend', 'order' => 2],
            ['name' => 'JavaScript (ES6+)', 'level' => 88, 'category' => 'Frontend', 'order' => 3],
            ['name' => 'Tailwind CSS', 'level' => 80, 'category' => 'Frontend', 'order' => 4],
            ['name' => 'Java / Spring Boot', 'level' => 85, 'category' => 'Backend', 'order' => 1],
            ['name' => 'Python / FastAPI', 'level' => 80, 'category' => 'Backend', 'order' => 2],
            ['name' => 'PHP', 'level' => 80, 'category' => 'Backend', 'order' => 3],
            ['name' => 'Django', 'level' => 75, 'category' => 'Backend', 'order' => 4],
            ['name' => 'MySQL', 'level' => 85, 'category' => 'Database', 'order' => 1],
            ['name' => 'PostgreSQL', 'level' => 80, 'category' => 'Database', 'order' => 2],
            ['name' => 'SQL Server', 'level' => 75, 'category' => 'Database', 'order' => 3],
            ['name' => 'Git / GitHub', 'level' => 90, 'category' => 'Tools & Practices', 'order' => 1],
            ['name' => 'Docker', 'level' => 75, 'category' => 'Tools & Practices', 'order' => 2],
            ['name' => 'AWS', 'level' => 65, 'category' => 'Tools & Practices', 'order' => 3],
            ['name' => 'Firebase', 'level' => 70, 'category' => 'Tools & Practices', 'order' => 4],
        ];

        foreach ($skills as $skill) {
            Skill::firstOrCreate(['name' => $skill['name']], $skill);
        }

        // ====== Experiences ======
        $experiences = [
            [
                'company' => 'Caro-game Project',
                'position' => 'Fullstack Developer',
                'description' => 'Developed a web-based Caro (Gomoku) game with interactive UI and real-time gameplay logic. Applied modern frontend practices to optimize rendering performance.',
                'start_date' => '2025-06-01',
                'end_date' => '2025-10-31',
                'current' => false,
                'order' => 1,
            ],
            [
                'company' => 'Laundry Management System',
                'position' => 'Back End Developer',
                'description' => 'Developed a web-based laundry management system. Implemented order lifecycle management with real-time status updates and built role-based access control (RBAC).',
                'start_date' => '2025-01-01',
                'end_date' => '2025-05-31',
                'current' => false,
                'order' => 2,
            ],
            [
                'company' => 'AIPower Company',
                'position' => 'Intern Fullstack Developer',
                'description' => 'Developed Education websites using WordPress and built company websites using PHP. Worked with HTML, CSS, JavaScript, PHP, and Postgresql.',
                'start_date' => '2024-01-01',
                'end_date' => '2024-12-31',
                'current' => false,
                'order' => 3,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::firstOrCreate(['company' => $exp['company'], 'position' => $exp['position']], $exp);
        }

        // ====== Technologies ======
        $techs = [
            ['name' => 'ReactJS', 'color' => '#61dafb'],
            ['name' => 'Spring Boot', 'color' => '#6db33f'],
            ['name' => 'FastAPI', 'color' => '#009688'],
            ['name' => 'NextJS', 'color' => '#000000'],
            ['name' => 'PHP', 'color' => '#777bb4'],
            ['name' => 'WordPress', 'color' => '#21759b'],
            ['name' => 'Python', 'color' => '#3776ab'],
            ['name' => 'MySQL', 'color' => '#4479a1'],
            ['name' => 'PostgreSQL', 'color' => '#336791'],
        ];

        $techModels = [];
        foreach ($techs as $tech) {
            $techModels[$tech['name']] = Technology::firstOrCreate(['name' => $tech['name']], $tech);
        }

        // ====== Projects ======
        $projects = [
            [
                'title' => 'Caro (Gomoku) Game',
                'description' => 'A web-based Caro game with interactive UI and real-time gameplay logic, responsive across desktop and mobile devices.',
                'github_url' => 'https://github.com/hoThanhThien',
                'demo_url' => 'https://caro-game-2025.fly.dev/',
                'featured' => true,
                'order' => 1,
                'techs' => ['ReactJS', 'FastAPI', 'Python'],
            ],
            [
                'title' => 'Laundry Management System',
                'description' => 'A web-based system to manage orders, customers, services, and payment workflows. Features order lifecycle management and RBAC.',
                'github_url' => 'https://github.com/hoThanhThien/LT_JAVA_010412213603',
                'demo_url' => null,
                'featured' => true,
                'order' => 2,
                'techs' => ['NextJS', 'Spring Boot', 'MySQL'],
            ],
            [
                'title' => 'AIPower Websites',
                'description' => 'Education websites and company portals focusing on responsive UI, user experience, and integrated backend functionality.',
                'github_url' => null,
                'demo_url' => 'https://hothanhthien.io.vn/',
                'featured' => true,
                'order' => 3,
                'techs' => ['PHP', 'WordPress', 'PostgreSQL'],
            ],
        ];

        foreach ($projects as $projData) {
            $techs = $projData['techs'];
            unset($projData['techs']);

            $project = Project::firstOrCreate(['title' => $projData['title']], $projData);
            $techIds = array_map(fn($t) => $techModels[$t]->id, array_filter($techs, fn($t) => isset($techModels[$t])));
            $project->technologies()->sync($techIds);
        }

        // ====== Blog Posts ======
        // We'll just keep the default dummy blog posts or change them to something related to Java/React
        $posts = [
            [
                'title' => 'Mastering React Hooks',
                'slug' => 'mastering-react-hooks',
                'excerpt' => 'Learn how to utilize React Hooks effectively for state management and functional components.',
                'content' => "React Hooks have completely changed how we write React components. In this post, we'll dive deep into useState, useEffect, and custom hooks to make your code cleaner and more reusable.",
                'published' => true,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Building RESTful APIs with Spring Boot',
                'slug' => 'building-restful-apis-with-spring-boot',
                'excerpt' => 'A comprehensive guide to creating scalable and secure REST APIs using Java and Spring Boot.',
                'content' => "Spring Boot makes it incredibly fast to get a production-ready application up and running. We will cover how to design your endpoints, implement JWT authentication, and secure your routes.",
                'published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::firstOrCreate(['slug' => $post['slug']], $post);
        }
    }
}
