<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_name',
        'event_year',
        'event_date',
        'event_start_time',
        'event_end_time',
        'event_lunar_date',
        'event_lunar_year',
        'event_type',
        'location',
        'description',
        'is_annual',
        'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_year' => 'integer',
        'is_annual' => 'boolean',
    ];

    public function families()
    {
        return $this->belongsToMany(Family::class, 'event_family', 'event_id', 'family_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_event', 'event_id', 'contact_id');
    }

    public function getDisplayTitleAttribute(): string
    {
        if (!empty($this->event_type)) {
            return $this->event_type;
        }

        if (!empty($this->event_name)) {
            return $this->event_name;
        }

        return 'Sự kiện';
    }
}
