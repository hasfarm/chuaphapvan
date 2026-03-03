<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class UsersImport implements ToCollection, WithHeadingRow, SkipsEmptyRows, WithChunkReading, WithBatchInserts
{
    use Importable;

    protected int $defaultRoleId;
    public int $createdCount = 0;
    public int $updatedCount = 0;

    public function __construct(int $defaultRoleId = 3)
    {
        $this->defaultRoleId = $defaultRoleId;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = [
                'fullname' => $row['fullname'] ?? null,
                'user_code' => $row['user_code'] ?? null,
                'email' => $row['email'] ?? null,
                'password' => $row['password'] ?? null,
                'role' => $row['role'] ?? null,
            ];

            // Skip empty rows - check if all required fields are empty
            if (empty($data['fullname']) && empty($data['user_code']) && empty($data['email']) && empty($data['password'])) {
                continue;
            }

            // Skip rows where only email is missing (completely empty row)
            if (empty($data['email']) && empty($data['user_code'])) {
                continue;
            }

            $validator = Validator::make($data, [
                'fullname' => 'required|string|max:255',
                'user_code' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|string|min:6',
                'role' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                $messages = implode('; ', $validator->errors()->all());
                throw new \RuntimeException('Dòng ' . ($index + 2) . ': ' . $messages);
            }

            $roleId = $this->resolveRoleId($data['role']);

            // Check if user exists
            $exists = User::where('email', $data['email'])->exists();

            // Perform upsert (update or create)
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'fullname' => $data['fullname'],
                    'user_code' => $data['user_code'],
                    'role_id' => $roleId,
                    'password' => Hash::make($data['password']),
                ]
            );

            // Track statistics
            if ($exists) {
                $this->updatedCount++;
            } else {
                $this->createdCount++;
            }
        }
    }

    private function resolveRoleId(?string $role): int
    {
        if (!$role) {
            return $this->defaultRoleId;
        }

        return Role::where('id', $role)
            ->orWhere('name', $role)
            ->orWhere('display_name', $role)
            ->value('id') ?? $this->defaultRoleId;
    }

    public function getStats(): array
    {
        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'total' => $this->createdCount + $this->updatedCount,
        ];
    }

    public function chunkSize(): int
    {
        return 100; // Process 100 rows at a time
    }

    public function batchSize(): int
    {
        return 100; // Insert 100 rows at a time
    }
}
