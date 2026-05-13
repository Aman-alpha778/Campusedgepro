<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactInquirySubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param array<string, mixed> $contactData
     */
    public function __construct(public array $contactData)
    {
    }

    public function envelope(): Envelope
    {
        $fullName = trim(($this->contactData['first_name'] ?? '').' '.($this->contactData['last_name'] ?? ''));

        return new Envelope(
            subject: 'New Contact Inquiry: '.($this->contactData['inquiry_type'] ?? 'General'),
            replyTo: [
                new Address(
                    $this->contactData['email'] ?? '',
                    $fullName !== '' ? $fullName : ($this->contactData['email'] ?? '')
                ),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-inquiry-submitted',
        );
    }
}
