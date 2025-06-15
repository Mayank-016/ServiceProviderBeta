<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookServiceRequest;
use App\Http\Requests\CreateOrUpdateScheduleRequest;
use App\Http\Requests\GetServiceSuppliers;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

use function Psy\debug;

class SupplierController extends Controller
{
    private $supplierService;
    public function __construct(SupplierService $supplierService){
        $this->supplierService = $supplierService;
    }
    public function getAvailableServices(Request $request){
        $resp = $this->supplierService->getAllServices();
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => null,
            'data'    => $resp
        ], Response::HTTP_OK);
    }

    

    public function manageServices(Request $request){
        $user = $request->user();
        $resp = $this->supplierService->manageService($user->id,$request);
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => 'Services Updated Successfully',
            'data'    => $resp
        ], Response::HTTP_OK);
    }

    public function createSchedule(CreateOrUpdateScheduleRequest $request){
        Log::info($request->post());
        $user = $request->user();
        $resp = $this->supplierService->createSchedule($user,$request);
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => null,
            'data'    => $resp
        ], Response::HTTP_OK);
    }

    public function getSuppliers(GetServiceSuppliers $request){
        $serviceId = $request->get('service_id');
        $data = $this->supplierService->getSuppliers($serviceId);
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => null,
            'data'    => $data
        ], Response::HTTP_OK);
    }

    public function bookService(BookServiceRequest $request){
        $user = $request->user();
        $this->supplierService->createBooking($user,$request);
        return response()->json([
            'success' => true,
            'status'  => Response::HTTP_OK,
            'message' => 'Booking Created Successfully!',
            'data'    => null,
        ], Response::HTTP_OK);
    }
}
