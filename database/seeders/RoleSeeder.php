<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo các role mặc định
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Quản Trị Viên',
                'description' => 'Có quyền truy cập đầy đủ đến tất cả các tính năng',
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Điều Hành Viên',
                'description' => 'Có quyền quản lý nội dung và người dùng',
            ],
            [
                'name' => 'user',
                'display_name' => 'Người Dùng Thường',
                'description' => 'Quyền truy cập cơ bản',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                ]
            );
        }
    }
}
