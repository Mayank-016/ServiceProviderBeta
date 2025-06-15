<?php

namespace App\Repositories;


use App\Interfaces\ServiceInterface;
use App\Models\Service;
use Illuminate\Support\Facades\Log;

class ServiceRepository implements ServiceInterface
{
    protected $model;

    public function __construct(Service $service)
    {
        $this->model = $service;
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

}
