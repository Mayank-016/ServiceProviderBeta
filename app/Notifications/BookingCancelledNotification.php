<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingCancelledNotification extends Notification
{
    use Queueable;

    public $from;
    public $to;
    public $bookingData;

    public function __construct($from, $to, $bookingData)
    {
        $this->from = $from;
        $this->to = $to;
        $this->bookingData = $bookingData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Cancelled')
            ->greeting('Hello ' . $notifiable->name)
            ->line("Your booking has been cancelled.")
            ->line('From: ' . $this->from->name . ' (' . $this->from->email . ')')
            ->line('To: ' . $this->to->name . ' (' . $this->to->email . ')')
            ->line('Service: ' . $this->bookingData['service'])
            ->line('Supplier Email: ' . $this->to->email)
            ->line('Booking Date: ' . $this->bookingData['date'])
            ->line('Slot: ' . $this->bookingData['slot']);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Booking cancelled!',
            'from' => $this->from->name,
            'to' => $this->to->name,
        ]);
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
