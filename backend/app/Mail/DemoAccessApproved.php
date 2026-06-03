<?php

namespace App\Mail;

use App\Models\DemoRequest;
use App\Models\DemoUser;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemoAccessApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public DemoRequest $demoRequest,
        public DemoUser $demoUser,
        public string $plainPassword,
        public string $loginUrl,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your CampusEdgePro Demo Access Details',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.demo-access-approved',
        );
    }
}
