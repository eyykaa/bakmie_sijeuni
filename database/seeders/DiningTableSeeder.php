<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiningTable;

class DiningTableSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DiningTable::updateOrCreate(
                ['table_no' => $i],
                ['is_active' => true]
            );
        }
    }
}