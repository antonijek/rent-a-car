<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConfirmationOfReservation extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation;
    public function __construct($reservation)
    {
       $this->reservation=$reservation;
    }
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Reservation made successfully!')
            ->view('mail.confirmation');
    }
}
