<?php

namespace App\Interfaces;

interface ProviderScheduleInterface extends BaseInterface
{
    public function getByProviderId($providerId);
}
