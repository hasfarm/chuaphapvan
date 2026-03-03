<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo user admin
        User::firstOrCreate(
            ['email' => 'admin@chuaphapvan-qc.com'],
            [
                'fullname' => 'Administrator',
                'user_code' => 'admin',
                'password' => Hash::make('123@abc'),
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => true,
                'role_id' => 1, // Admin role
            ]
        );

        // Tạo user moderator (optional)
        User::firstOrCreate(
            ['email' => 'moderator@chuaphapvan-qc.com'],
            [
                'fullname' => 'Moderator',
                'user_code' => 'moderator',
                'password' => Hash::make('123@abc'),
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => true,
                'role_id' => 2, // Moderator role
            ]
        );

        // Tạo user yêu cầu bởi người dùng
        User::firstOrCreate(
            ['email' => 'dongnguyenthe123@gmail.com'],
            [
                'fullname' => 'Dong Nguyen The',
                'user_code' => 'dongnguyenthe123',
                'password' => Hash::make('123@abc'),
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => true,
                'role_id' => 3, // Regular user role
            ]
        );

        // Danh sách các user thường
        $users = [
            'Văn',
            'Wel',
            'Nga',
            'Diệp',
            'Gơn',
            'Minh',
            'Nhung',
            'Thanh',
        ];

        // Tạo các user từ danh sách
        $userRole = 3; // User role ID
        foreach ($users as $index => $name) {
            $email = strtolower(str_replace(' ', '', $name)) . '@chuaphapvan-qc.com';
            User::firstOrCreate(
                ['email' => $email],
                [
                    'fullname' => $name,
                    'user_code' => 'user_' . ($index + 1),
                    'password' => Hash::make('123@abc'),
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'is_verified' => true,
                    'role_id' => $userRole,
                ]
            );
        }
    }
}
