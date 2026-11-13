<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'Thien Ho Admin',
            'email' => 'admin@portfolio.test',
            'password' => Hash::make('Secret1234!'),
        ]);
    }

    public function test_guest_cannot_access_admin_profile()
    {
        $this->get('/admin/profile')->assertRedirect('/admin/login');
        $this->put('/admin/profile', ['name' => 'Hack', 'email' => 'hack@test.com'])->assertRedirect('/admin/login');
        $this->put('/admin/profile/password', [])->assertRedirect('/admin/login');
    }

    public function test_authenticated_admin_can_view_profile_with_real_cms_data()
    {
        Project::create([
            'title' => 'Real Portfolio Project',
            'description' => 'Actual project stored in DB',
            'featured' => true,
        ]);

        BlogPost::create([
            'title' => 'Real Engineering Article',
            'slug' => 'real-engineering-article',
            'content' => '<p>Content</p>',
            'published' => true,
        ]);

        $token = $this->admin->createToken('Laptop Workstation');

        $response = $this->actingAs($this->admin)->get('/admin/profile');

        $response->assertStatus(200)
            ->assertSee('Thien Ho Admin')
            ->assertSee('admin@portfolio.test')
            ->assertSee('Laptop Workstation')
            ->assertSee('Real Portfolio Project')
            ->assertSee('Real Engineering Article');
    }

    public function test_admin_can_update_personal_information()
    {
        $response = $this->actingAs($this->admin)->put('/admin/profile', [
            'name' => 'Ho Thanh Thien',
            'email' => 'thien.updated@portfolio.test',
        ]);

        $response->assertRedirect(route('admin.profile.index', ['tab' => 'overview']))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'Ho Thanh Thien',
            'email' => 'thien.updated@portfolio.test',
        ]);
    }

    public function test_admin_can_update_password_with_valid_current_password()
    {
        $badResponse = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'WrongPassword',
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ]);

        $badResponse->assertRedirect(route('admin.profile.index', ['tab' => 'security']))
            ->assertSessionHasErrors('current_password');

        $okResponse = $this->actingAs($this->admin)->put('/admin/profile/password', [
            'current_password' => 'Secret1234!',
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ]);

        $okResponse->assertRedirect(route('admin.profile.index', ['tab' => 'security']))
            ->assertSessionHas('success');

        $this->admin->refresh();
        $this->assertTrue(Hash::check('NewSecret123!', $this->admin->password));
    }

    public function test_admin_can_revoke_own_sanctum_token_only()
    {
        $ownToken = $this->admin->createToken('CLI Script')->accessToken;

        $otherUser = User::create([
            'name' => 'Other Admin',
            'email' => 'other@portfolio.test',
            'password' => Hash::make('Password123!'),
        ]);
        $otherToken = $otherUser->createToken('Other Token')->accessToken;

        $this->actingAs($this->admin)
            ->delete('/admin/profile/tokens/' . $otherToken->id)
            ->assertRedirect(route('admin.profile.index', ['tab' => 'security']))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('personal_access_tokens', ['id' => $otherToken->id]);

        $this->actingAs($this->admin)
            ->delete('/admin/profile/tokens/' . $ownToken->id)
            ->assertRedirect(route('admin.profile.index', ['tab' => 'security']))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('personal_access_tokens', ['id' => $ownToken->id]);
    }
}

