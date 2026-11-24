<?php

namespace Database\Seeders;

use App\Models\Report;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $reports = [
            ['mistnost' => '364', 'kategorie' => 'IT', 'status' => 'Nové', 'created_at' => '2024-07-08'],
            ['mistnost' => 'Chodba 3. patro', 'kategorie' => 'Elektro', 'status' => 'V Řešení', 'created_at' => '2024-07-20'],
            ['mistnost' => '364', 'kategorie' => 'Nábytek', 'status' => 'V Řešení', 'created_at' => '2024-07-31'],
            ['mistnost' => '364', 'kategorie' => 'Nábytek', 'status' => 'Nové', 'created_at' => '2024-02-29'],
            ['mistnost' => '364', 'kategorie' => 'Elektro', 'status' => 'Vyřešeno', 'created_at' => '2024-02-17'],
            ['mistnost' => 'Chodba 3. patro', 'kategorie' => 'Nábytek', 'status' => 'Vyřešeno', 'created_at' => '2024-02-04'],
        ];

        foreach ($reports as $data) {
            Report::create($data);
        }
    }
}
