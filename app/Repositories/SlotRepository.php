<?php

namespace App\Repositories;


use App\Interfaces\SlotInterface;
use App\Models\Slot;
use App\Models\User;

class SlotRepository implements SlotInterface
{
    protected $model;

    public function __construct(Slot $slot)
    {
        $this->model = $slot;
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
    public function getRecentBookings($supplierId)
    {
        $today = now()->toDateString();
        $oneWeekLater = now()->addWeek()->toDateString();

        $slots = $this->model->where('provider_id', $supplierId)
            ->whereDate('booking_date', '>=', $today)
            ->whereDate('booking_date', '<=', $oneWeekLater)
            ->select('id', 'start_time', 'end_time', 'booking_date')
            ->get();
        return $slots;
    }
    public function canBook($supplierId,$startDateTime,$endDateTime,$bookingDate)  {
        $exists = $this->model->where('provider_id', $supplierId)
            ->where('booking_date', $bookingDate->toDateString())
            ->where('start_time',$startDateTime)
            ->where('end_time',$endDateTime)
            ->where('is_booked', true)
            ->exists();
        //If Not exists then We can book else Not
        return !$exists;
    }
}
