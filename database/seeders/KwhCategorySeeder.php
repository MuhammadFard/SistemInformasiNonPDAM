<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KwhCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kwh_categories')->insert([
            ['daya' => '450', 'tarif_bulanan' => 50000, 'created_at' => now(), 'updated_at' => now()],
            ['daya' => '900-2200', 'tarif_bulanan' => 120000, 'created_at' => now(), 'updated_at' => now()],
            ['daya' => '3500-5500', 'tarif_bulanan' => 300000, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
