<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ServiceRepository;
use App\Services\SupplierService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $categoryRepository;
    private $supplierService;
    private $serviceRepository;
    private $bookingRepository;
    public function __construct(
        CategoryRepository $categoryRepository,
        SupplierService $supplierService,
        ServiceRepository $serviceRepository,
        BookingRepository $bookingRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->supplierService = $supplierService;
        $this->serviceRepository = $serviceRepository;
        $this->bookingRepository = $bookingRepository;
    }
    public function home()
    {
        return view('auth.form');
    }
    public function dashboard(Request $request)
    {
        $allCategory = $this->categoryRepository->allWithService();
        $user = $request->user();
        if ($user->role === ROLE_USER) {
            $bookings = $this->bookingRepository->getUserBookings($user->id);
            return view('user_dashboard', ['categories' => $allCategory, 'user' => $request->user(), "userBookings" => $bookings]);
        }
        if ($user->providerSchedules()->count() == 0) {
            return view('supplier_schedule', $allCategory);
        }
        $providerServices = $this->supplierService->getProvidesServices($user);

        return view('supplier_dashboard', [
            'availableServices' => $providerServices
        ]);
    }
    public function manageServices(Request $request)
    {
        $user = $request->user();
        $allServices = $this->categoryRepository->allWithService();
        $providerServices = $this->supplierService->getProvidesServices($user);
        return view('supplier_update_service', [
            'allServicesCategorized' => $allServices,
            'supplierServices' => $providerServices
        ]);
    }

    public function showBookingPage(Request $request, $service_id)
    {
        $service = $this->serviceRepository->find($service_id);
        $serviceName = $service ? $service->name : 'Unknown Service';

        return view('user_service_booking', [
            'serviceId' => $service_id,
            'serviceName' => $serviceName,
        ]);
    }
    public function myBookings(Request $request)
    {
        $user = $request->user();
        $bookings = $this->bookingRepository->getUserBookings($user->id);
        return view('user_bookings', [
            'bookings' => $bookings
        ]);
    }
}
