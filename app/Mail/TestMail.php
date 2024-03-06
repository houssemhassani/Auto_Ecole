<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $token;

    public function __construct($user, $password, $token)
    {
        $this->user = $user;
        $this->password = $password;
        $this->token = $token;
    }

    public function build()
    {
        $loginUrl = url('/login') . '?token=' . $this->token; // Construire l'URL de connexion avec le token
        
        return $this->subject('Welcome to Auto-Ecole App, ' . $this->user->name)
                    ->view('emails.email')
                    ->with([
                        'user' => $this->user,
                        'password' => $this->password,
                        'loginUrl' => $loginUrl, // Passer l'URL de connexion à la vue de l'email
                    ]);
    }
}
