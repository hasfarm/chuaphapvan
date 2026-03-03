<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_name',
        'product_code',
        'description',
        'price',
        'color',
        'variety',
        'shelf_life_days',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}
