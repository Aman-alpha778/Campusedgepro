<?php

namespace Tests\Feature;

use App\Mail\DemoAccessApproved;
use App\Models\DemoRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class DemoRequestWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_request_form_stores_request_in_database(): void
    {
        $response = $this->from('/demo.html')->post('/demo-request', [
            'college_name' => 'Green Valley College',
            'admin_name' => 'Riya Sen',
            'email' => 'riya@example.com',
            'phone' => '9999999999',
            'student_strength' => '1800',
            'requirements' => 'Need a walkthrough of fees, attendance, and reports.',
        ]);

        $response->assertRedirect('/demo.html');
        $response->assertSessionHas('demo_request_success');

        $this->assertDatabaseHas('demo_requests', [
            'college_name' => 'Green Valley College',
            'admin_name' => 'Riya Sen',
            'email' => 'riya@example.com',
            'status' => 'Pending',
        ]);
    }

    public function test_admin_can_approve_demo_request_and_send_credentials(): void
    {
        Mail::fake();

        $admin = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $demoRequest = DemoRequest::query()->create([
            'college_name' => 'North Star Institute',
            'admin_name' => 'Aditya Rao',
            'email' => 'aditya@example.com',
            'phone' => '8888888888',
            'student_strength' => '2500',
            'requirements' => 'Complete ERP walkthrough.',
            'status' => 'Pending',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.demo-requests.approve', $demoRequest));

        $response->assertRedirect();
        $response->assertSessionHas('admin_success');

        $demoRequest->refresh();
        $demoUser = $demoRequest->demoUser()->first();

        $this->assertSame('Approved', $demoRequest->status);
        $this->assertNotNull($demoUser);
        $this->assertSame('Active', $demoUser->status);
        $this->assertTrue($demoUser->expiry_date->greaterThan(now()->addDays(6)));

        Mail::assertSent(DemoAccessApproved::class, function (DemoAccessApproved $mail) use ($demoRequest, $demoUser): bool {
            return $mail->demoRequest->is($demoRequest)
                && $mail->demoUser->is($demoUser)
                && Hash::check($mail->plainPassword, $demoUser->password);
        });
    }
}
