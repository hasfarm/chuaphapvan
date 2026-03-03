<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Greenhouse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'farm_id',
        'greenhouse_name',
        'greenhouse_code',
        'area_size',
        'type',
        'description',
    ];

    /**
     * Relationship: Greenhouse belongs to Farm
     */
    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }
}
