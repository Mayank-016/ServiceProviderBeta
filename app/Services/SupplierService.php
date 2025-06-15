<?php

namespace App\Services;

use App\Events\BookingConfirmed;
use App\Exceptions\InvalidInputException;
use App\Models\Slot;
use App\Models\User;
use App\Repositories\BookingRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ProviderScheduleRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\ServiceUserRepository;
use App\Repositories\SlotRepository;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

use function Psy\debug;

class SupplierService
{
    private $categoryRepository;
    private $serviceRepository;
    private $providerScheduleRepository;
    private $serviceUserRepository;
    private $bookingRepository;
    private $userRepository;
    private $slotRepository;
    public function __construct(
        CategoryRepository $categoryRepository,
        ServiceRepository $serviceRepository,
        ProviderScheduleRepository $providerScheduleRepository,
        ServiceUserRepository $serviceUserRepository,
        BookingRepository $bookingRepository,
        SlotRepository $slotRepository,
        UserRepository $userRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->serviceRepository = $serviceRepository;
        $this->providerScheduleRepository = $providerScheduleRepository;
        $this->serviceUserRepository = $serviceUserRepository;
        $this->bookingRepository = $bookingRepository;
        $this->slotRepository = $slotRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllServices()
    {
        return $this->serviceRepository->all();
    }
    /**
     * Returns All the Service being Offered By An Supplier
     * @param \App\Models\User $user
     * @return Collection
     */
    public function getProvidesServices(User $user)
    {
        return $user->services()->get();
    }

    public function createSchedule(User $user, $request)
    {
        $data = [
            'provider_id' => $user->id,
            'on_weekend' => (bool) $request->post('weekend_available'),
            'start_time' => Carbon::createFromFormat('H:i:s', $request->post('start_time')),
            'end_time' => Carbon::createFromFormat('H:i:s', $request->post('end_time')),
            'slot_duration' => $request->post('slot_duration'),
        ];
        $schedule = $this->providerScheduleRepository->create($data);
        return $schedule;
    }

    public function manageService($userId, $request)
    {
        $selectedServices = $request->post('selected_services') ?? [];
        $removedServices = $request->post('removed_services') ?? [];

        if (!empty($selectedServices))
            foreach ($selectedServices as $serviceData) {
                $serviceId = $serviceData['service_id'];
                $price = $serviceData['price'];
                $this->serviceUserRepository->addOrUpdateSupplierService($userId, $serviceId, $price);
            }
        if (!empty($removedServices))
            foreach ($removedServices as $serviceData) {
                $serviceId = $serviceData['service_id'];
                $this->serviceUserRepository->removeSupplierService($userId, $serviceId);
            }
        return true;
    }

    public function getSuppliers($serviceId)
    {
        $serviceProviders = $this->serviceUserRepository->getAllSuppliers($serviceId);
        $result = [];
        foreach ($serviceProviders as $provider) {
            $supplier = $provider->supplier;

            if (!$supplier) {
                continue;
            }
            $supplierData = $supplier->toArray();
            $supplierData['price'] = $provider->price;
            $supplierData['service_id'] = $provider->service_id;
            $supplierData['service_name'] = $provider->service->name;
            $supplierData['bookings'] = $this->slotRepository->getRecentBookings($supplier->id);
            $result[] = $supplierData;
        }
        return $result;
    }

    public function createBooking(User $user, $request)
    {
        $supplierId = $request->post('supplier_id');
        $serviceId = $request->post('service_id');
        $date = $request->post('date');
        $startTime = $request->post('start_time');
        $endTime = $request->post('end_time');

        $bookingDate = Carbon::parse($date);
        $startTime = Carbon::createFromFormat('H:i:s', $startTime)->format('H:i:s');
        $endTime = Carbon::createFromFormat('H:i:s', $endTime)->format('H:i:s');

        // Rule 1: Prevent booking in the past
        if ($bookingDate->isBefore(today())) {
            throw new InvalidInputException("You cannot book a slot in the past.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Rule 2: Prevent booking more than B days in advance
        $maxDays = 7; // Set B here
        if ($bookingDate->gt(today()->addDays($maxDays))) {
            throw new InvalidInputException("You cannot book more than $maxDays days in advance.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Rule 3: Check supplier schedule
        $schedule = $this->providerScheduleRepository->getByProviderId($supplierId);
        if (!$schedule) {
            throw new InvalidInputException("Supplier not found.", Response::HTTP_NOT_FOUND);
        }

        // Skip weekends if supplier doesn't work on weekends
        $isWeekend = in_array($bookingDate->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY]);
        if (!$schedule->on_weekend && $isWeekend) {
            throw new InvalidInputException("This supplier does not work on weekends.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        // Check working hours
        if (
            $startTime < $schedule->start_time ||
            $endTime > $schedule->end_time
        ) {
            throw new InvalidInputException("Selected time is outside of supplier's working hours.", Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $startDateTime = $bookingDate->copy()->setTimeFromTimeString($startTime)->toDateTimeString();
        $endDateTime = $bookingDate->copy()->setTimeFromTimeString($endTime)->toDateTimeString();
        if (!$this->slotRepository->canBook($supplierId, $startDateTime, $endDateTime, $bookingDate)) {
            throw new InvalidInputException("This slot overlaps with an existing booking.", Response::HTTP_CONFLICT);
        }

        $slot = $slot = $this->slotRepository->create([
            'provider_id' => $supplierId,
            'service_id' => $serviceId,
            'start_time' => $startDateTime,
            'end_time' => $endDateTime,
            'booking_date' => $bookingDate->toDateString(),
            'is_booked' => true,
        ]);

        $booking = $this->bookingRepository->create([
            'user_id' => $user->id,
            'slot_id' => $slot->id,
            'service_id' => $serviceId,
            'supplier_id' => $supplierId,
            'status' => STATUS_BOOKED,
        ]);

        $supplier = $this->userRepository->find($supplierId);
        $service = $booking->service;

        $bookingData = [
            'slot' => $slot->start_time . " - " . $slot->end_time,
            'date' => $slot->booking_date,
            'supplier' => $supplier->name,
            'service' => $service->name
        ];

        event(new BookingConfirmed($user, $supplier,$bookingData));
    }


}