<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $type
    ) {}

    public function build()
    {
        $subject = match ($this->type) {
            'login' => 'Votre code de connexion — Bourse Pour Tous',
            'password_reset' => 'Réinitialisation de mot de passe — Bourse Pour Tous',
            default => 'Votre code de vérification — Bourse Pour Tous',
        };

        return $this->subject($subject)
            ->markdown('mail.otp-code')
            ->with(['code' => $this->code]);
    }
}