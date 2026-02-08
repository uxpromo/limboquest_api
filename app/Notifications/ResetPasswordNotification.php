<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $url;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $url)
    {
        $this->url = $url;
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
     * todo: Убрать хардкод строк
     */
    public function toMail(object $notifiable): MailMessage
    {
        $count = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        return (new MailMessage)
            ->subject('Запрос на сброс пароля')
            ->line('Вы получили это письмо, потому что был сделан запрос на сброс пароля.')
            ->action('Сбросить пароль', $this->url)
            ->line("Ссылка на сброс пароля будет действительна в течение $count минут.")
            ->line('Если Вы не запрашивали сброс пароля, то просто проигнорируйте это письмо');
    }
}
