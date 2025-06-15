<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class BookingConfirmedNotification extends Notification implements ShouldQueue
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

    public function via($notifiable)
    {
        return ['mail', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Booking Confirmed')
            ->greeting('Hello ' . $notifiable->name)
            ->line('A booking has been confirmed.')
            ->line('From: ' . $this->from->name)
            ->line('To: ' . $this->to->name)
            ->line('Service: ' . $this->bookingData['service'])
            ->line('Supplier Email: ' . $this->to->email)
            ->line('Booking Date: ' . $this->bookingData['date'])
            ->line('Slot: ' . $this->bookingData['slot']);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'Booking confirmed!',
            'from' => $this->from->name,
            'to' => $this->to->name,
        ]);
    }
}
