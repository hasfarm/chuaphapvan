<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditPointsConfig extends Model
{
    use HasFactory;

    protected $table = 'audit_points_config';

    protected $fillable = [
        'field_name',
        'display_name',
        'points',
        'is_active',
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get all active point configurations as an associative array
     * @return array [field_name => points]
     */
    public static function getActivePoints()
    {
        return static::where('is_active', true)
            ->pluck('points', 'field_name')
            ->toArray();
    }
}
