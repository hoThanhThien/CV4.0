<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Project;
use App\Models\BlogPost;
use App\Models\Skill;
use App\Models\Experience;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'Test Admin',
            'email' => 'admin@test.local',
            'password' => Hash::make('password123'),
        ]);
    }

    public function test_api_documentation_endpoints()
    {
        $response = $this->get('/api/docs');
        $response->assertStatus(200);

        $specResponse = $this->get('/api/docs/openapi.json');
        $specResponse->assertStatus(200)
                     ->assertJsonPath('openapi', '3.0.0');
    }

    public function test_get_projects_api()
    {
        Project::create([
            'title' => 'Personal Portfolio & CMS',
            'description' => 'Test description',
            'featured' => true,
        ]);

        $response = $this->getJson('/api/v1/projects');
        $response->assertStatus(200)
                 ->assertJsonPath('success', true)
                 ->assertJsonCount(1, 'data');
    }

    public function test_get_skills_and_experiences_api()
    {
        Skill::create(['name' => 'PHP', 'level' => 90, 'category' => 'Backend']);
        Experience::create(['company' => 'Freelance', 'position' => 'Dev', 'description' => 'Desc', 'start_date' => '2025-01-01']);

        $skillsRes = $this->getJson('/api/v1/skills');
        $skillsRes->assertStatus(200)->assertJsonPath('success', true);

        $expRes = $this->getJson('/api/v1/experiences');
        $expRes->assertStatus(200)->assertJsonPath('success', true);
    }

    public function test_contact_submission_api()
    {
        $payload = [
            'name' => 'Recruiter John',
            'email' => 'john@company.com',
            'subject' => 'Job Interview',
            'message' => 'We are impressed with your portfolio and would like to invite you for an interview.',
        ];

        $response = $this->postJson('/api/v1/contact', $payload);
        $response->assertStatus(201)
                 ->assertJsonPath('success', true);

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@company.com',
        ]);
    }

    public function test_auth_and_blog_crud_api()
    {
        // 1. Login
        $loginRes = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@test.local',
            'password' => 'password123',
        ]);

        $loginRes->assertStatus(200)
                 ->assertJsonPath('success', true);

        $token = $loginRes->json('token');
        $headers = ['Authorization' => 'Bearer ' . $token];

        // 2. Create Blog Post (Protected)
        $createRes = $this->postJson('/api/v1/blog', [
            'title' => 'Optimizing Laravel APIs with Sanctum',
            'content' => 'Full article content for testing...',
            'published' => true,
        ], $headers);

        $createRes->assertStatus(201)
                  ->assertJsonPath('success', true);

        $postId = $createRes->json('data.id');
        $slug = $createRes->json('data.slug');

        // 3. Get Single Post by Slug (Public)
        $getRes = $this->getJson('/api/v1/blog/' . $slug);
        $getRes->assertStatus(200)
               ->assertJsonPath('data.title', 'Optimizing Laravel APIs with Sanctum');

        // 4. Update Blog Post (Protected)
        $updateRes = $this->putJson('/api/v1/blog/' . $postId, [
            'title' => 'Updated Article Title',
        ], $headers);

        $updateRes->assertStatus(200)
                  ->assertJsonPath('data.title', 'Updated Article Title');

        // 5. Delete Blog Post (Protected)
        $deleteRes = $this->deleteJson('/api/v1/blog/' . $postId, [], $headers);
        $deleteRes->assertStatus(200)
                  ->assertJsonPath('success', true);
    }

    public function test_skills_crud_api()
    {
        $user = User::first();
        $token = $user->createToken('test-token')->plainTextToken;
        $headers = ['Authorization' => 'Bearer ' . $token];

        // 1. Create skill (Protected)
        $createRes = $this->postJson('/api/v1/skills', [
            'name' => 'Docker',
            'category' => 'Tools & Practices',
            'level' => 85,
            'order' => null,
        ], $headers);
        $createRes->assertStatus(201)
                  ->assertJsonPath('success', true)
                  ->assertJsonPath('data.name', 'Docker')
                  ->assertJsonPath('data.order', 0);

        $skillId = $createRes->json('data.id');

        // 2. Show skill (Public)
        $showRes = $this->getJson('/api/v1/skills/' . $skillId);
        $showRes->assertStatus(200)
                ->assertJsonPath('data.name', 'Docker');

        // 3. Update skill (Protected)
        $updateRes = $this->putJson('/api/v1/skills/' . $skillId, [
            'level' => 95,
        ], $headers);
        $updateRes->assertStatus(200)
                  ->assertJsonPath('data.level', 95);

        // 4. Delete skill (Protected)
        $deleteRes = $this->deleteJson('/api/v1/skills/' . $skillId, [], $headers);
        $deleteRes->assertStatus(200)
                  ->assertJsonPath('success', true);
    }

    public function test_experiences_crud_api()
    {
        $user = User::first();
        $token = $user->createToken('test-token')->plainTextToken;
        $headers = ['Authorization' => 'Bearer ' . $token];

        // 1. Create experience (Protected)
        $createRes = $this->postJson('/api/v1/experiences', [
            'company' => 'Acme Inc',
            'position' => 'Senior Developer',
            'description' => 'Built high-scale APIs with Laravel',
            'start_date' => '2025-01-01',
            'current' => true,
            'order' => null,
        ], $headers);
        $createRes->assertStatus(201)
                  ->assertJsonPath('success', true)
                  ->assertJsonPath('data.company', 'Acme Inc')
                  ->assertJsonPath('data.order', 0)
                  ->assertJsonPath('data.is_current', true);

        $expId = $createRes->json('data.id');

        // 2. Show experience (Public)
        $showRes = $this->getJson('/api/v1/experiences/' . $expId);
        $showRes->assertStatus(200)
                ->assertJsonPath('data.company', 'Acme Inc');

        // 3. Update experience (Protected)
        $updateRes = $this->putJson('/api/v1/experiences/' . $expId, [
            'position' => 'Lead Architect',
        ], $headers);
        $updateRes->assertStatus(200)
                  ->assertJsonPath('data.position', 'Lead Architect');

        // 4. Delete experience (Protected)
        $deleteRes = $this->deleteJson('/api/v1/experiences/' . $expId, [], $headers);
        $deleteRes->assertStatus(200)
                  ->assertJsonPath('success', true);
    }

    public function test_technologies_crud_api()
    {
        $user = User::first();
        $token = $user->createToken('test-token')->plainTextToken;
        $headers = ['Authorization' => 'Bearer ' . $token];

        // 1. Create technology (Protected)
        $createRes = $this->postJson('/api/v1/technologies', [
            'name' => 'Redis Cache',
            'color' => '#dc2626',
        ], $headers);
        $createRes->assertStatus(201)
                  ->assertJsonPath('success', true)
                  ->assertJsonPath('data.name', 'Redis Cache');

        $techId = $createRes->json('data.id');

        // 2. Get technologies (Public)
        $listRes = $this->getJson('/api/v1/technologies');
        $listRes->assertStatus(200)
                ->assertJsonPath('success', true);

        // 3. Update technology (Protected)
        $updateRes = $this->putJson('/api/v1/technologies/' . $techId, [
            'color' => '#b91c1c',
        ], $headers);
        $updateRes->assertStatus(200)
                  ->assertJsonPath('data.color', '#b91c1c');

        // 4. Delete technology (Protected)
        $deleteRes = $this->deleteJson('/api/v1/technologies/' . $techId, [], $headers);
        $deleteRes->assertStatus(200)
                  ->assertJsonPath('success', true);
    }

    public function test_admin_web_controllers_nullable_order()
    {
        $user = User::first();

        // Admin skill store with empty order
        $resSkill = $this->actingAs($user)->post(route('admin.skills.store'), [
            'name' => 'GraphQL',
            'category' => 'Backend',
            'order' => '',
        ]);
        $resSkill->assertRedirect(route('admin.skills.index'));
        $this->assertDatabaseHas('skills', ['name' => 'GraphQL', 'order' => 0]);

        // Admin experience store with empty order
        $resExp = $this->actingAs($user)->post(route('admin.experiences.store'), [
            'company' => 'Global Corp',
            'position' => 'Software Engineer',
            'description' => 'Worked on microservices',
            'start_date' => '2025-02-01',
            'order' => '',
        ]);
        $resExp->assertRedirect(route('admin.experiences.index'));
        $this->assertDatabaseHas('experiences', ['company' => 'Global Corp', 'order' => 0]);

        // Admin project store with empty order
        $resProj = $this->actingAs($user)->post(route('admin.projects.store'), [
            'title' => 'E-Commerce Platform',
            'description' => 'Full-featured online store',
            'order' => '',
        ]);
        $resProj->assertRedirect(route('admin.projects.index'));
        $this->assertDatabaseHas('projects', ['title' => 'E-Commerce Platform', 'order' => 0]);
    }
}

