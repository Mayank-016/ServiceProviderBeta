<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ServiceUser extends Pivot
{

    public $timestamps = true;

    protected $table = 'service_user';

    protected $fillable = [
        'service_id',
        'user_id',
        'price',
    ];



    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
