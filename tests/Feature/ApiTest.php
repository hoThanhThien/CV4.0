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
            'name' => 'Ho Thanh Thien',
            'email' => 'hothanhthien119@gmail.com',
            'password' => Hash::make('password'),
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
            'email' => 'hothanhthien119@gmail.com',
            'password' => 'password',
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
}
