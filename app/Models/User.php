<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * User bookings (as customer)
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Services offered by the user (if supplier)
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_user')
            ->withPivot('price')
            ->withTimestamps();
    }

    /**
     * Provider schedules if user is supplier
     */
    public function providerSchedules()
    {
        return $this->hasMany(ProviderSchedule::class, 'provider_id');
    }

    /**
     * Slots belonging to the user (supplier)
     */
    public function slots()
    {
        return $this->hasMany(Slot::class, 'provider_id');
    }

    /**
     * Bookings as a supplier (through slots or direct)
     */
    public function supplierBookings()
    {
        return $this->hasMany(Booking::class, 'supplier_id');
    }
}
