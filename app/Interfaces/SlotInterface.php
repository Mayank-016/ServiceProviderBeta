<?php

namespace App\Interfaces;

interface SlotInterface extends BaseInterface
{
    public function getRecentBookings($supplierId);
    public function canBook($supplierId,$startDateTime,$endDateTime,$bookingDate);
}
