<?php

namespace Tests\Feature;

use App\Mail\DemoAccessApproved;
use App\Models\DemoRequest;
use App\Models\DemoUser;
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
                && $mail->hasTo($demoRequest->email)
                && Hash::check($mail->plainPassword, $demoUser->password);
        });
    }

    public function test_admin_can_search_demo_requests_across_request_details_and_credentials(): void
    {
        $admin = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $matchingRequest = DemoRequest::query()->create([
            'college_name' => 'Silver Oak College',
            'admin_name' => 'Meera Shah',
            'email' => 'meera@example.com',
            'phone' => '9000011111',
            'student_strength' => '1800',
            'requirements' => 'Needs a transport and fee module walkthrough.',
            'status' => 'Approved',
        ]);

        DemoUser::query()->create([
            'request_id' => $matchingRequest->id,
            'username' => 'demo_silver_oak',
            'password' => 'secret123',
            'expiry_date' => now()->addWeek(),
            'status' => 'Active',
        ]);

        DemoRequest::query()->create([
            'college_name' => 'North Star Institute',
            'admin_name' => 'Aditya Rao',
            'email' => 'aditya@example.com',
            'phone' => '9000022222',
            'student_strength' => '2500',
            'requirements' => 'Complete ERP walkthrough.',
            'status' => 'Pending',
        ]);

        $this
            ->actingAs($admin)
            ->get(route('admin.demo-requests.index', ['search' => 'silver oak']))
            ->assertOk()
            ->assertSee('Silver Oak College')
            ->assertDontSee('North Star Institute');

        $this
            ->actingAs($admin)
            ->get(route('admin.demo-requests.index', ['search' => 'transport']))
            ->assertOk()
            ->assertSee('Silver Oak College')
            ->assertDontSee('North Star Institute');

        $this
            ->actingAs($admin)
            ->get(route('admin.demo-requests.index', ['search' => 'demo_silver']))
            ->assertOk()
            ->assertSee('Silver Oak College')
            ->assertDontSee('North Star Institute');
    }

    public function test_admin_can_resend_demo_access_credentials_to_request_email(): void
    {
        Mail::fake();

        $admin = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'secret123',
            'is_admin' => true,
        ]);

        $demoRequest = DemoRequest::query()->create([
            'college_name' => 'Riverdale College',
            'admin_name' => 'Kavya Menon',
            'email' => 'kavya@example.com',
            'phone' => '9000033333',
            'student_strength' => '1200',
            'requirements' => 'Needs admissions CRM demo.',
            'status' => 'Approved',
        ]);

        $demoUser = DemoUser::query()->create([
            'request_id' => $demoRequest->id,
            'username' => 'demo_riverdale',
            'password' => 'old-password',
            'expiry_date' => now()->addDay(),
            'status' => 'Active',
        ]);

        $response = $this
            ->actingAs($admin)
            ->post(route('admin.demo-requests.resend-access', $demoRequest));

        $response->assertRedirect();
        $response->assertSessionHas('admin_success');

        $demoUser->refresh();

        Mail::assertSent(DemoAccessApproved::class, function (DemoAccessApproved $mail) use ($demoRequest, $demoUser): bool {
            return $mail->demoRequest->is($demoRequest)
                && $mail->demoUser->is($demoUser)
                && $mail->hasTo($demoRequest->email)
                && $mail->demoUser->username === 'demo_riverdale'
                && Hash::check($mail->plainPassword, $demoUser->password);
        });
    }
}
