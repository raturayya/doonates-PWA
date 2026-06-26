<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AccountApproved extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $loginUrl = route('login');

        return (new MailMessage)
            ->subject('🎉 Your Doonates Account Has Been Approved!')
            ->markdown('emails.account-approved', [
                'user'     => $notifiable,
                'loginUrl' => $loginUrl,
            ]);
    }
}
