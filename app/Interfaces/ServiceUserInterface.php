<?php

namespace App\Interfaces;

interface ServiceUserInterface extends BaseInterface
{
    public function addOrUpdateSupplierService($userId,$serviceId,$price);
    public function removeSupplierService($userId,$serviceId);
    public function getAllSuppliers($serviceId);
}
