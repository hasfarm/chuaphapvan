<?php

namespace App\Imports;

use App\Models\Farm;
use App\Models\Greenhouse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GreenhousesImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public int $createdCount = 0;
    public int $updatedCount = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = [
                'farm' => $row['farm'] ?? null,
                'greenhouse_name' => $row['greenhouse_name'] ?? null,
                'greenhouse_code' => $row['greenhouse_code'] ?? null,
                'area_size' => $row['area_size'] ?? null,
                'type' => $row['type'] ?? null,
                'description' => $row['description'] ?? null,
            ];

            $validator = Validator::make($data, [
                'farm' => 'required',
                'greenhouse_name' => 'required|string|max:255',
                'greenhouse_code' => 'required|string|max:255',
                'area_size' => 'nullable|integer|min:0',
                'type' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $messages = implode('; ', $validator->errors()->all());
                throw new \RuntimeException('Dòng ' . ($index + 2) . ': ' . $messages);
            }

            $farmId = $this->resolveFarmId($data['farm']);

            if (!$farmId) {
                throw new \RuntimeException('Dòng ' . ($index + 2) . ': Không tìm thấy trang trại "' . $data['farm'] . '"');
            }

            // Check if greenhouse exists
            $exists = Greenhouse::where('greenhouse_code', $data['greenhouse_code'])->exists();

            // Perform upsert (update or create)
            Greenhouse::updateOrCreate(
                ['greenhouse_code' => $data['greenhouse_code']],
                [
                    'farm_id' => $farmId,
                    'greenhouse_name' => $data['greenhouse_name'],
                    'area_size' => $data['area_size'],
                    'type' => $data['type'],
                    'description' => $data['description'],
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

    private function resolveFarmId($farm): ?int
    {
        if (!$farm) {
            return null;
        }

        return Farm::where('id', $farm)
            ->orWhere('farm_name', $farm)
            ->orWhere('farm_code', $farm)
            ->value('id');
    }

    public function getStats(): array
    {
        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'total' => $this->createdCount + $this->updatedCount,
        ];
    }
}
