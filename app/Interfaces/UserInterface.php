<?php

namespace App\Interfaces;

interface UserInterface extends BaseInterface
{
    public function findByEmail($email);
}
