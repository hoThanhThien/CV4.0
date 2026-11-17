<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_cron_endpoint_rejects_missing_or_invalid_token_when_secret_configured()
    {
        config(['services.cron.secret' => 'super-secret-cron-token']);

        $this->get('/cron/schedule-run')->assertStatus(403);
        $this->get('/cron/schedule-run?token=wrong-token')->assertStatus(403);
        $this->withHeaders(['X-Cron-Token' => 'wrong-token'])
             ->get('/cron/schedule-run')
             ->assertStatus(403);
    }

    public function test_cron_endpoint_accepts_valid_query_or_header_token()
    {
        config(['services.cron.secret' => 'super-secret-cron-token']);

        $this->get('/cron/schedule-run?token=super-secret-cron-token')
             ->assertStatus(200)
             ->assertJsonPath('status', 'ok');

        $this->withHeaders(['X-Cron-Token' => 'super-secret-cron-token'])
             ->get('/cron/schedule-run')
             ->assertStatus(200)
             ->assertJsonPath('status', 'ok');
    }

    public function test_setup_endpoints_block_anonymous_requests_in_production()
    {
        config(['services.cron.secret' => 'setup-secret-123']);
        $previousEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $this->get('/setup-migrate')->assertStatus(403);
            $this->get('/setup-db')->assertStatus(403);
            $this->get('/setup-storage')->assertStatus(403);
            $this->get('/setup-migrate?token=invalid-token')->assertStatus(403);

            $this->withHeaders(['X-Cron-Token' => 'setup-secret-123'])
                 ->get('/setup-migrate')
                 ->assertStatus(200)
                 ->assertJsonPath('status', 'ok');

            $admin = User::create([
                'name' => 'Admin User',
                'email' => 'ops-admin@test.local',
                'password' => Hash::make('StrongPass123!'),
            ]);

            $this->actingAs($admin)
                 ->get('/setup-migrate')
                 ->assertStatus(200)
                 ->assertJsonPath('status', 'ok');
        } finally {
            $this->app['env'] = $previousEnv;
        }
    }

    public function test_rate_limiters_are_registered_and_enforce_limits_outside_testing()
    {
        $this->assertNotNull(RateLimiter::limiter('auth-strict'));
        $this->assertNotNull(RateLimiter::limiter('contact-form'));

        $previousEnv = $this->app['env'];
        $this->app['env'] = 'production';

        try {
            $loginIp = ['REMOTE_ADDR' => '198.51.100.11'];
            for ($i = 0; $i < 6; $i++) {
                $this->withServerVariables($loginIp)
                     ->postJson('/api/v1/auth/login', [
                         'email' => 'nobody@test.local',
                         'password' => 'wrong-pass',
                     ])
                     ->assertStatus(401);
            }

            $this->withServerVariables($loginIp)
                 ->postJson('/api/v1/auth/login', [
                     'email' => 'nobody@test.local',
                     'password' => 'wrong-pass',
                 ])
                 ->assertStatus(429);

            $contactIp = ['REMOTE_ADDR' => '198.51.100.22'];
            for ($i = 0; $i < 5; $i++) {
                $this->withServerVariables($contactIp)
                     ->postJson('/api/v1/contact', [
                         'name' => 'Tester',
                         'email' => 'tester@test.local',
                         'subject' => 'Subject ' . $i,
                         'message' => 'Hello, testing contact form throttle threshold safely.',
                     ])
                     ->assertStatus(201);
            }

            $this->withServerVariables($contactIp)
                 ->postJson('/api/v1/contact', [
                     'name' => 'Tester',
                     'email' => 'tester@test.local',
                     'subject' => 'Subject Over Limit',
                     'message' => 'Hello, testing contact form throttle threshold safely.',
                 ])
                 ->assertStatus(429);
        } finally {
            $this->app['env'] = $previousEnv;
        }
    }
}
