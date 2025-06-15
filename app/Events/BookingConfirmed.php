<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $supplier;
    public $bookingData;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, User $supplier, $bookingData)
    {
        $this->user = $user->refresh();
        $this->supplier = $supplier->refresh();
        $this->bookingData = $bookingData;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user'.$this->user->id),
            new PrivateChannel('supplier'.$this->supplier->id),
        ];
    }
    public function broadcastAs(): string
    {
        return 'booking.confirmed';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => 'Booking confirmed!',
            'user_name' => $this->user->name,
            'supplier_name' => $this->supplier->name,
        ];
    }
}
