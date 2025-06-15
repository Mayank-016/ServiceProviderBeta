<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProviderSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider_id',
        'on_weekend',
        'start_time',
        'end_time',
        'slot_duration',
        'is_active',
    ];

    protected $hidden = [
        'id',
        'provider_id',
        'created_at',
        'updated_at',
        'is_active'
    ];


    protected $casts = [
        // 'start_time' => 'datetime:H:i:s',
        // 'end_time' => 'datetime:H:i:s',
        'is_active' => 'boolean',
        'on_weekend' => 'boolean',
    ];

    /**
     * ProviderSchedule belongs to a provider (User with supplier role)
     */
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
