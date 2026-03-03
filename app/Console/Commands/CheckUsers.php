<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckUsers extends Command
{
    protected $signature = 'check:users';
    protected $description = 'Check users with role_id';

    public function handle()
    {
        $users = User::with('role')->select('id', 'name', 'email', 'role_id')->get();

        $this->info('=== DANH SÁCH USERS ===');
        $this->table(
            ['ID', 'Tên', 'Email', 'Role ID', 'Role Name'],
            $users->map(fn($user) => [
                $user->id,
                $user->name,
                $user->email,
                $user->role_id,
                $user->role?->role_name ?? ($user->role?->name ?? 'N/A'),
            ])->toArray()
        );

        $this->info("\n✅ Tất cả " . $users->count() . " users đã được tạo!");
    }
}
