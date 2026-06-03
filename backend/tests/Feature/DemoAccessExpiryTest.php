<?php

namespace Tests\Feature;

use App\Models\DemoRequest;
use App\Models\DemoUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoAccessExpiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_expired_demo_user_cannot_log_in(): void
    {
        $request = DemoRequest::query()->create([
            'college_name' => 'Sunrise College',
            'admin_name' => 'Neha Kapoor',
            'email' => 'neha@example.com',
            'phone' => '7777777777',
            'student_strength' => '3000',
            'requirements' => 'Need admissions and student module access.',
            'status' => 'Approved',
        ]);

        DemoUser::query()->create([
            'request_id' => $request->id,
            'username' => 'demo_sunrise_college',
            'password' => 'ExpiredPass12',
            'expiry_date' => now()->subDay(),
            'status' => 'Active',
        ]);

        $response = $this->from(route('demo.login'))->post(route('demo.login.store'), [
            'username' => 'demo_sunrise_college',
            'password' => 'ExpiredPass12',
        ]);

        $response->assertRedirect(route('demo.login'));
        $response->assertSessionHasErrors('username');
        $this->assertGuest('demo');
        $this->assertDatabaseHas('demo_users', [
            'username' => 'demo_sunrise_college',
            'status' => 'Expired',
        ]);
    }
}
