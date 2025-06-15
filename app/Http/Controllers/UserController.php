<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancelBookingRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    private $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }
    public function getProfile(Request $request){
        $user = $request->user();
    }
    public function getBookings(Request $request)
    {
        $res = $this->userService->getBookings($request->user());
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => null,
            'data'    => $res,
        ], Response::HTTP_OK);
    }
    Public function cancelBooking(CancelBookingRequest $request)
    {
        $user = $request->user();
        $resp = $this->userService->cancelBooking($user,$request);
        if($resp['success']){
            return response()->json([
                'success' => true,
                'status'  => Response::HTTP_OK,
                'message' => $resp['message'],
                'data'    => null,
            ], Response::HTTP_OK);
        }
        return response()->json([
            'success' => false,
            'status'  => Response::HTTP_BAD_REQUEST,
            'message' => $resp['message'],
            'data'    => null,
        ], Response::HTTP_BAD_REQUEST);
    }
}
