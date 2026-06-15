<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class StaffResetPassword extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        $url = route('staff.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ]);

        return (new MailMessage)
            ->subject('Restablece tu contraseña de administrador - RUPAL')
            ->view('emails.staff-reset-password', [
                'url' => $url,
                'user' => $notifiable,
                'count' => config('auth.passwords.staff_users.expire'),
            ]);
    }
}
