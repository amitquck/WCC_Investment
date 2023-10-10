<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SentPrescriptionMail extends Mailable
{
    use Queueable, SerializesModels;
    public $sentPrescription;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sentPrescription)
    {

         $this->sentPrescription = $sentPrescription;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        // ->from('quick@infotech.co.in')
            
    $sentPrescription = 'Hi,welcome user!';
    // dump($sentPrescription)
    return $this->html($sentPrescription)->subject($sentPrescription);
    }
}
