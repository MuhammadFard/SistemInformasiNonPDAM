<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('customers')->insert([
            'user_id' => 1,
            'alamat' => 'Jl. Merdeka No. 10, Bandung',
            'rt' => '-',
            'rw' => '-',
            'nomor_telepon' => '08123456789',
            'kwh_category_id' => 1, // Pastikan kategori KWH sudah ada
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
