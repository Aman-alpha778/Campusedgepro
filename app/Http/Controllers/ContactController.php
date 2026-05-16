<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquirySubmitted;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ContactController extends Controller
{
    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_type' => ['required', 'string', 'in:General,Booking,Pricing,Modules,Others'],
            'message' => ['nullable', 'string', 'max:5000'],
            'updates' => ['nullable', 'accepted'],
        ]);

        $payload = [
            ...$validated,
            'updates' => $request->boolean('updates'),
        ];

        Inquiry::create([
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'] ?? null,
            'country' => $payload['country'] ?? null,
            'phone' => $payload['phone'],
            'email' => $payload['email'],
            'inquiry_type' => $payload['inquiry_type'],
            'message' => $payload['message'] ?? null,
            'wants_updates' => $payload['updates'],
        ]);

        $recipientAddress = config('mail.to.address');
        $recipientName = config('mail.to.name');

        try {
            Mail::to($recipientAddress, $recipientName)
                ->send(new ContactInquirySubmitted($payload));
        } catch (\Throwable $e) {
            $failureId = 'mail-'.Str::lower(Str::random(12));

            Log::error('Contact inquiry email send failed.', [
                'failure_id' => $failureId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'recipient_address' => $recipientAddress,
                'recipient_name' => $recipientName,
                'contact_email' => $payload['email'],
                'inquiry_type' => $payload['inquiry_type'],
            ]);

            return back()
                ->withInput()
                ->with('contact_error', 'Your inquiry was saved, but we could not send the email right now. Reference ID: '.$failureId)
                ->with('contact_error_details', config('mail.show_error_details') ? $e->getMessage() : null);
        }

        return back()->with('contact_success', 'Thanks. Your inquiry has been sent successfully.');
    }
}
