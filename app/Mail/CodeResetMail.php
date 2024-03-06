<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CodeResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    //public $password;
    public $code;

    public function __construct($user, $code)
    {
        $this->user = $user;
        //$this->password = $password;
        $this->code = $code;
    }

    public function build()
    {
        //$loginUrl = url('/login') . '?token=' . $this->token; // Construire l'URL de connexion avec le token
        
        return $this->subject('Reset Password Auto-Ecole App !!, ' . $this->user->name)
                    ->view('emails.emailCode')
                    ->with([
                        'user' => $this->user,
                        'code' => $this->code,
                        //'loginUrl' => $loginUrl, // Passer l'URL de connexion à la vue de l'email
                    ]);
    }
}
