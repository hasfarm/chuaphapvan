<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Các cột có thể gán hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Các giá trị được chuyển đổi
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Quan hệ: Role có nhiều Users
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Kiểm tra role có phải là admin không
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin';
    }

    /**
     * Kiểm tra role có phải là moderator không
     */
    public function isModerator(): bool
    {
        return $this->name === 'moderator';
    }
}
