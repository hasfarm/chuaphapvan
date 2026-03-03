<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'family_name',
        'family_code',
        'head_name',
        'phone',
        'email',
        'address',
        'notes',
        'chart_layout',
        'status',
    ];

    protected $casts = [
        'chart_layout' => 'array',
    ];

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function householdHead()
    {
        return $this->hasOne(Contact::class)->where('is_household_head', true);
    }

    public function primaryContact()
    {
        return $this->hasOne(Contact::class)->where('is_primary_contact', true);
    }
}
