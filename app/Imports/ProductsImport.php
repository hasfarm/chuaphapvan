<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    use Importable;

    public int $createdCount = 0;
    public int $updatedCount = 0;
    public int $skippedCount = 0;

    public function headingRow(): int
    {
        return 1;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $data = [
                'product_name' => $row['product_name'] ?? null,
                'product_code' => $row['product_code'] ?? null,
                'description' => $row['description'] ?? null,
                'price' => $row['price'] ?? null,
                'color' => $row['color'] ?? null,
                'variety' => $row['variety'] ?? null,
                'shelf_life_days' => $row['shelf_life_days'] ?? null,
            ];

            $validator = Validator::make($data, [
                'product_name' => 'required|string|max:255',
                'product_code' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'nullable|numeric|min:0',
                'color' => 'nullable|string|max:255',
                'variety' => 'nullable|string|max:255',
                'shelf_life_days' => 'nullable|integer|min:0',
            ]);

            if ($validator->fails()) {
                $messages = implode('; ', $validator->errors()->all());
                throw new \RuntimeException('Dòng ' . ($index + 2) . ': ' . $messages);
            }

            // Check if product exists
            $exists = Product::where('product_name', $data['product_name'])->exists();

            // Perform upsert (update or create)
            Product::updateOrCreate(
                ['product_name' => $data['product_name']],
                [
                    'product_code' => $data['product_code'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'color' => $data['color'],
                    'variety' => $data['variety'],
                    'shelf_life_days' => $data['shelf_life_days'],
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

    public function getStats(): array
    {
        return [
            'created' => $this->createdCount,
            'updated' => $this->updatedCount,
            'total' => $this->createdCount + $this->updatedCount,
        ];
    }
}
