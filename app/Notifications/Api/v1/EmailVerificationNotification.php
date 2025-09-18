<?php

namespace App\Notifications\Api\v1;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    public int $userId;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'api.verify.verifyEmail', now()->addDay(1), ['user' => $this->userId]
        );

        return (new MailMessage)
            ->subject('Lorditheme - email verification')
            ->line('This email is sent to you by lorditheme.com following your
             request for email verification or registration of a new account.')
            ->action('Verify Email', $url)
            ->line('After pressing the button above you will be redirected to a page and your email will be verified.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
