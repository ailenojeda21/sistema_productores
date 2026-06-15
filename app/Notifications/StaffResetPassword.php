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

        $expire = config('auth.passwords.staff_users.expire', 60);

        return (new MailMessage)
            ->subject('Restablece tu contraseña de administrador - RUPAL')
            ->greeting('¡Hola!')
            ->line('Has solicitado restablecer la contraseña de tu cuenta de administrador del sistema RUPAL.')
            ->action('Restablecer contraseña', $url)
            ->line("Este enlace expirará en {$expire} minutos.")
            ->line('Si no solicitaste este cambio, podés ignorar este mensaje.');
    }
}
