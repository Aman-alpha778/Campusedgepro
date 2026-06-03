<?php

namespace App\Mail;

use App\Models\DemoRequest;
use App\Models\DemoUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemoExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public DemoRequest $demoRequest,
        public DemoUser $demoUser,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your CampusEdgePro Demo Access Expires Soon',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demo-expiry-reminder',
        );
    }
}
