<?php

namespace App\Repositories;


use App\Interfaces\ProviderScheduleInterface;
use App\Models\ProviderSchedule;

class ProviderScheduleRepository implements ProviderScheduleInterface
{
    protected $model;

    public function __construct(ProviderSchedule $providerSchedule)
    {
        $this->model = $providerSchedule;
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
    public function getByProviderId($providerId)
    {
        return $this->model->where('provider_id',$providerId)->first();
    }

}
