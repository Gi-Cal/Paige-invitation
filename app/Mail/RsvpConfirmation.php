<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RsvpConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $rsvpData;

    public function __construct($rsvpData)
    {
        $this->rsvpData = $rsvpData;
    }

    public function build()
    {
        return $this->subject('Your RSVP Confirmation - Paige\'s First Birthday')
                    ->view('emails.rsvp-confirmation');
    }
}