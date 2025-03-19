<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendCronMail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;

    // Constructor mein data pass karein
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    // Mail ka build method
    public function build()
    {
        return $this->subject($this->emailData['subject'])
                    ->view('mail.cronMail')
                    ->with('emailData', $this->emailData);
    }
}

