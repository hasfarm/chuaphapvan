<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_id',
        'is_household_head',
        'is_primary_contact',
        'full_name',
        'dharma_name',
        'phone',
        'email',
        'solar_birth_date',
        'solar_birth_year',
        'lunar_birth_date',
        'lunar_birth_year',
        'gender',
        'life_status',
        'death_solar_date',
        'death_lunar_date',
        'death_lunar_year',
        'family_name',
        'family_head_name',
        'address',
        'family_address',
        'zodiac_info',
        'notes',
        'status',
    ];

    protected $casts = [
        'solar_birth_date' => 'date',
        'death_solar_date' => 'date',
        'solar_birth_year' => 'integer',
        'is_household_head' => 'boolean',
        'is_primary_contact' => 'boolean',
    ];

    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function relationshipsOut()
    {
        return $this->hasMany(ContactRelationship::class, 'contact_id');
    }

    public function relationshipsIn()
    {
        return $this->hasMany(ContactRelationship::class, 'related_contact_id');
    }
}
