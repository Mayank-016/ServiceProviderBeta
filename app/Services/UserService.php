<?php

namespace App\Services;

use App\Events\BookingCancelled;
use App\Exceptions\InvalidCredentialsException;
use App\Models\User;
use App\Repositories\BookingRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

class UserService
{
    private $userRepository;
    private $bookingRepository;

    public function __construct(UserRepository $userRepository,BookingRepository $bookingRepository)
    {
        $this->userRepository = $userRepository;
        $this->bookingRepository = $bookingRepository;
    }

    public function getProfile(User $user)
    {
        return [
            'role' => $user->role == ROLE_SUPPLIER ? 'Suplier' : 'User',
            'bookings' => $user->bookings(),
        ];
    }
    public function getBookings(User $user){ 
        return $this->bookingRepository->getUserBookings($user->id);
    }

    public function cancelBooking(User $user,$request)
    {
        $bookingId = $request->post('booking_id');
        $booking = $this->bookingRepository->find($bookingId);
        if($booking){
            //Booking is Older than One hour
            if($booking->created_at < now()->subHour()){
                return [
                'success' => false,
                'message' => "Can't Cancel a booking after One hour!"
                ];
            }
            else{
                $service = $booking->service;
                $slot = $booking->slot;
                $supplier = $booking->supplier;
                $slot->delete();
                $booking->delete();
                $bookingData = [
                    'slot' => $slot->start_time . " - " . $slot->end_time,
                    'date' => $slot->booking_date,
                    'supplier' => $supplier->name,
                    'service' => $service->name
                ];
                event(new BookingCancelled($user,$supplier,$bookingData));
                return [
                    'success' => true,
                    'message' => "Booking canceled successfully !"
                ];
            }
        }
        else{
            throw new InvalidArgumentException("Invalid Booking Id!",Response::HTTP_BAD_REQUEST);
        }
    }

}
