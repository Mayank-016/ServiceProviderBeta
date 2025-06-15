<?php

namespace App\Repositories;


use App\Interfaces\ServiceUserInterface;
use App\Models\ServiceUser;
use Illuminate\Support\Facades\Log;

class ServiceUserRepository implements ServiceUserInterface
{
    protected $model;

    public function __construct(ServiceUser $serviceUser)
    {
        $this->model = $serviceUser;
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

    public function addOrUpdateSupplierService($userId, $serviceId, $price)
    {

        $userService = $this->model->where([
            'user_id' => $userId,
            'service_id' => $serviceId
        ])->first();

        if ($userService) {

            $userService->price = $price;
            $userService->save();
        } else {

            $this->model->create([
                'service_id' => $serviceId,
                'user_id' => $userId,
                'price' => $price
            ]);
        }
    }

    public function removeSupplierService($userId, $serviceId)
    {
        $userService = $this->model->where([
            'user_id' => $userId,
            'service_id' => $serviceId
        ])->first();

        if ($userService) {
            // If the record exists, delete it
            $userService->delete();
        } else {
            Log::warning("Service not found for user: {$userId} with service: {$serviceId}");
        }
    }

    public function getAllSuppliers($serviceId)
    {
        return $this->model->where('service_id', $serviceId)->with(['supplier.providerSchedules','service:id,name'])->get();
    }


}
