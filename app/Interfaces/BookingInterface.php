<?php

namespace App\Interfaces;

interface BookingInterface extends BaseInterface
{
    public function getRecentBookings($id);
    public function getUserBookings($userId);

}
