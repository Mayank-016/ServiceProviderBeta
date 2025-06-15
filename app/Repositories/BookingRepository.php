<?php

namespace App\Repositories;

use App\Interfaces\BookingInterface;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

use function Psy\debug;

class BookingRepository implements BookingInterface
{
    protected $model;

    public function __construct(Booking $booking)
    {
        $this->model = $booking;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update($id, array $attributes)
    {
        $user = $this->model->find($id);
        $user->update($attributes);
        return $user;
    }

    public function delete($id)
    {
        $user = $this->model->find($id);
        return $user->delete();
    }
    public function getRecentBookings($userId)
    {
        $today = now()->toDateString();
        $oneWeekLater = now()->addWeek()->toDateString();
        $recentBookings = $this->model
            ->where('supplier_id', $userId)
            ->whereHas('slot', function ($query) use ($today, $oneWeekLater) {
                $query->whereDate('booking_date', '>=', $today)
                    ->whereDate('booking_date', '<=', $oneWeekLater);
            })
            ->with(['slot:id,start_time,end_time,booking_date'])
            ->get();
        return $recentBookings;
    }

    public function getUserBookings($userId)
    {
        $bookings = $this->model
            ->where('user_id', $userId)
            ->with([
                'service:id,name',
                'slot:id,start_time,end_time,booking_date',
                'supplier' => function ($query) {
                    $query->select('id', 'name')->with([
                        'services' => function ($q) {
                            $q->select('services.id', 'name')->withPivot('price');
                        }
                    ]);
                }
            ])
            ->select('id', 'user_id', 'service_id', 'slot_id', 'supplier_id') // select only what is used
            ->get();
    
        return $bookings->map(function ($booking) {
            $slot = $booking->slot;
            $service = $booking->service;
            $supplier = $booking->supplier;
    
            // Find the matching service in the supplier's services to get pivot price
            $supplierService = $supplier->services->firstWhere('id', $service->id);
    
            return [
                'supplier_id' => $supplier->id,
                'service_id' => $service->id,
                'booking_id' => $booking->id,
                'slot_id' => $slot->id,
                'supplier' => $supplier->name,
                'service' => $service->name,
                'start_time' => $slot->start_time,
                'end_time' => $slot->end_time,
                'booking_date' => $slot->booking_date,
                'price' => $supplierService?->pivot->price,
                'booking_created_at' => $booking->created_at
            ];
        })->toArray();
    }    

}
