<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquirySubmitted;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Throwable;

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
            Mail::to($recipientAddress, $recipientName)->send(new ContactInquirySubmitted($payload));
        } catch (Throwable) {
            return back()
                ->withInput()
                ->withErrors(['contact' => 'We could not send your inquiry right now. Please try again in a moment.']);
        }

        return back()->with('contact_success', 'Thanks. Your inquiry has been sent successfully.');
    }
}
