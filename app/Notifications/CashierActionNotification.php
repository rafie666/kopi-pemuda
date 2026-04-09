<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CashierActionNotification extends Notification
{
    use Queueable;

    protected $message;
    protected $userName;

    /**
     * Create a new notification instance.
     */
    public function __construct($userName, $message)
    {
        $this->userName = $userName;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_name' => $this->userName,
            'message' => $this->message,
            'time' => now()->format('H:i:s'),
        ];
    }
}
