<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ParamMailCustomer extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $mailData;

    public function __construct($mailData)
    {
        $this->mailData = $mailData; // Assign passed data
    }

    public function build()
    {
        return $this->view('mail.parameter.customer') // Email view file
                    ->subject('Job Complete Notification') // Email subject
                    ->with('mailData', $this->mailData); // Pass data to view
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
