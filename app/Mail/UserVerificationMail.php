<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // Cambia el nombre de la propiedad

    public function __construct($code)
    {
        $this->code = $code; // Cambia aquí también
    }

    public function build()
    {
        return $this->subject('Código de Verificación - Sistema')
                    ->view('emails.user-verification')
                    ->with(['code' => $this->code]); // Aquí está bien
    }
}