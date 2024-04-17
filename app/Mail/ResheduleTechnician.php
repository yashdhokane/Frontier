<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResheduleTechnician extends Mailable
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
            subject: 'Reshedule Job Update -'
        );
    }



    /**
     * Get the message content definition.
     */
     public function build()
    {
        return $this->view('mail.reschedule.ResheduleTechnician') 
            ->with('maildata', $this->maildata); 
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