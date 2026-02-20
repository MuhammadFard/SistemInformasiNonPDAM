<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('invoices')->insert([
            'customer_id' => 1,
            'nomor_invoice' => '07C10000',
            'total_tagihan' => 50000,
            'tanggal_bayar' => now(),
            'tanggal_jatuh_tempo' => now()->addDays(30),
            'status' => 'unpaid',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
