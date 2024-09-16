<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MyCustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $maildata;

    /**
     * Create a new message instance.
     */
    public function __construct($maildata)
    {
        $this->maildata = $maildata;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->maildata['subject'], 
        );
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Use the correct concatenation in the view path with dot notation
        return $this->from($this->maildata['from'])
                    ->view('mail.schedule.' . $this->maildata['type'])
                    ->with('maildata', $this->maildata);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
