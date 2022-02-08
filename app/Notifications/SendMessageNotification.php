<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendMessageNotification extends Notification
{
    use Queueable;

    public Message $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->salutation(__('mail.salutation'))
            ->greeting(__('mail.greeting'))
            ->subject(__('mail.subject', ['uuid' => $this->message->uuid]))
            ->line(__('mail.content', [
                    'email' => $this->message->email,
                    'message' => $this->message->content
                ]
            ));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
