<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    /**
     * Relationship: A service belongs to one category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relationship: A service can be offered by many users (suppliers).
     */
    public function suppliers()
    {
        return $this->belongsToMany(User::class, 'service_user')
            ->withPivot('price')
            ->withTimestamps();
    }
}
