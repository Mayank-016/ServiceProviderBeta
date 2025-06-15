<?php

namespace App\Listeners;

use App\Events\BookingConfirmed;
use App\Notifications\BookingConfirmedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingConfirmedNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(BookingConfirmed $event): void
    {
        $event->user->notify(new BookingConfirmedNotification($event->supplier, $event->user,$event->bookingData));
        $event->supplier->notify(new BookingConfirmedNotification($event->user, $event->supplier,$event->bookingData));
    }
}
