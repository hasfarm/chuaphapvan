<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Audit extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'date',
        'greenhouse_id',
        'greenhouse_name',
        'qc_name',
        'picker_code',
        'worker_name',
        'variety_name',
        'plot_code',
        'bag_weight',
        'qty',
        'uniformity_qty',
        'urc_weight_qty',
        'length_qty',
        'damaged_qty',
        'leaf_burn_qty',
        'yellow_spot_qty',
        'wooden_qty',
        'dirty_qty',
        'wrong_label_qty',
        'pest_disease_qty',
        'total_points',
        'user_id',
    ];

    /**
     * Attribute casts
     */
    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship: Audit belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate total defects
     */
    public function calculateTotalDefect(): int
    {
        return $this->leaf_yellow_spot + $this->leaf_yellow_vein +
            $this->wooden_spot + $this->dirty_damage + $this->pest_disease;
    }

    /**
     * Calculate defect rate percentage
     */
    public function getDefectRateAttribute(): float
    {
        if ($this->qty_quantity == 0) return 0;
        return ($this->total_defect / $this->qty_quantity) * 100;
    }

    /**
     * Scopes
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeByGreenhouse($query, $greenhouse_id)
    {
        return $query->where('greenhouse_id', $greenhouse_id);
    }

    public function scopeByQC($query, $qc_name)
    {
        return $query->where('qc_name', $qc_name);
    }

    public function scopeByWorker($query, $worker_name)
    {
        return $query->where('worker_name', $worker_name);
    }

    public function scopeByVariety($query, $variety_name)
    {
        return $query->where('variety_name', $variety_name);
    }

    public function scopeSearchGlobal($query, $search)
    {
        return $query->where('picker_code', 'like', "%{$search}%")
            ->orWhere('worker_name', 'like', "%{$search}%")
            ->orWhere('variety_name', 'like', "%{$search}%")
            ->orWhere('plot_code', 'like', "%{$search}%");
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('date', [$from, $to]);
    }
}
