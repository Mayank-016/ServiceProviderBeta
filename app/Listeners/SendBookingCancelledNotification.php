<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Notifications\BookingCancelledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendBookingCancelledNotification implements ShouldQueue
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
    public function handle(BookingCancelled $event): void
    {
        $event->user->notify(new BookingCancelledNotification($event->supplier, $event->user, $event->bookingData));
        $event->supplier->notify(new BookingCancelledNotification($event->user, $event->supplier,$event->bookingData));
    }
}
