<?php

namespace App\Notifications;

use App\Mail\WelcomeVerificationMail;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class RupalWelcomeVerification extends Notification
{
    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): WelcomeVerificationMail
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new WelcomeVerificationMail($notifiable, $verificationUrl));
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
        );
    }
}
