<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function getSuppliers(Request $request){
        $serviceId = $request->get('service_id');
        
    }
}
