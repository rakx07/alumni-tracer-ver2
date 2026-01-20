<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class NDMUVerifyEmail extends VerifyEmail
{
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function toMail($notifiable)
    {
        $verifyUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('NDMU Alumni Portal – Email Verification')
            ->greeting('Good day!')
            ->line('You are receiving this email because you registered for the **NDMU Alumni Portal**.')
            ->line('Please verify your email address to complete your registration.')
            ->action('Verify Email Address', $verifyUrl)
            ->line('If you did not create an account, no further action is required.')
            ->salutation('Regards,')
            ->salutation('NDMU Alumni Portal Team');
    }
}
