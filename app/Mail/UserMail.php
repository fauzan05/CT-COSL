<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserMail extends Mailable
{
    use Queueable, SerializesModels;
    private array $data;
    private string $viewName;
    private string $subjectText;
    private array $customAttachments = [];

    /**
     * Create a new message instance.
     */
    public function __construct(array $data = [], string $view = 'emails.default', string $subject = 'User Mail', array $attachments = [])
    {
        $this->data = $data;
        $this->viewName = $view;
        $this->subjectText = $subject;
        $this->attachments = $attachments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->viewName,
            with: $this->data
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        if (empty($this->customAttachments)) {
            return [];
        }

        return array_map(
            fn($filePath) => Attachment::fromPath($filePath),
            array_filter($this->customAttachments, fn($path) => !empty($path) && file_exists($path))
        );
    }
}
