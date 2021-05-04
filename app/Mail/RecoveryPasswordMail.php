<?php

namespace App\Mail;

use App\RecoveryPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RecoveryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private $recoveryPassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(RecoveryPassword $recoveryPassword)
    {
        $this->recoveryPassword = $recoveryPassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->recoveryPassword->user->email;
        $token = $this->recoveryPassword->token;

        $this->to($email);
        $this->subject('Recuperação de senha');

        $url = env('APP_URL_FRONTEND').'/password/reset/'.$token;

        return $this->markdown('mail.recoveryPassword', [
            'url' => $url
        ]);
    }
}
