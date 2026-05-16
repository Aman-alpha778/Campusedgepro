<?php

namespace Tests\Feature;

use App\Mail\ContactInquirySubmitted;
use App\Models\Inquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_an_email(): void
    {
        Mail::fake();

        $response = $this->post('/contact', [
            'first_name' => 'Aman',
            'last_name' => 'Sharma',
            'country' => 'India',
            'phone' => '9876543210',
            'email' => 'aman@example.com',
            'inquiry_type' => 'Pricing',
            'message' => 'Please share full pricing details.',
            'updates' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('contact_success');

        Mail::assertSent(ContactInquirySubmitted::class, function (ContactInquirySubmitted $mail) {
            return $mail->contactData['first_name'] === 'Aman'
                && $mail->contactData['email'] === 'aman@example.com'
                && $mail->contactData['inquiry_type'] === 'Pricing'
                && $mail->contactData['updates'] === true;
        });

        $this->assertDatabaseHas(Inquiry::class, [
            'first_name' => 'Aman',
            'email' => 'aman@example.com',
            'inquiry_type' => 'Pricing',
            'wants_updates' => true,
        ]);
    }

    public function test_contact_form_handles_mail_failures_gracefully(): void
    {
        Mail::partialMock()
            ->shouldReceive('to')
            ->once()
            ->andReturnSelf();

        Mail::shouldReceive('send')
            ->once()
            ->andThrow(new \RuntimeException('SMTP transport failed.'));

        $response = $this->from('/contact.html')->post('/contact', [
            'first_name' => 'Aman',
            'last_name' => 'Sharma',
            'country' => 'India',
            'phone' => '9876543210',
            'email' => 'aman@example.com',
            'inquiry_type' => 'Pricing',
            'message' => 'Please share full pricing details.',
            'updates' => '1',
        ]);

        $response->assertRedirect('/contact.html');
        $response->assertSessionHas('contact_error');

        $this->assertDatabaseHas(Inquiry::class, [
            'first_name' => 'Aman',
            'email' => 'aman@example.com',
            'inquiry_type' => 'Pricing',
            'wants_updates' => true,
        ]);
    }
}
