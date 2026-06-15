<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class UserResetPassword extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Restablece tu contraseña - RUPAL')
            ->view('emails.user-reset-password', [
                'url' => $url,
                'user' => $notifiable,
                'count' => config('auth.passwords.users.expire'),
            ]);
    }
}
