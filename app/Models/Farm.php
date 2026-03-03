<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_name',
        'farm_code',
        'description',
        'location',
        'phone',
        'manager_name',
    ];

    /**
     * Relationship: Farm has many greenhouses
     */
    public function greenhouses()
    {
        return $this->hasMany(Greenhouse::class);
    }

    /**
     * Get the count of greenhouses
     */
    public function getGreenhouseCountAttribute()
    {
        return $this->greenhouses()->count();
    }
}
