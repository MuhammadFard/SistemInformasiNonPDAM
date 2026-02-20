<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Customer
        DB::table('users')->insert([
            'nama' => 'Bobi Kertanegara',
            'email' => 'bobikertanegara@gmail.com',
            'password' => Hash::make('12345'),
            'role' => 'customer',
            'tanggal_terdaftar' => now(),
            'account_type' => 'simple',
            'is_verified' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('users')->insert([
            'nama' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('123456'),
            'role' => 'superadmin',
            'tanggal_terdaftar' => now(),
            'account_type' => 'full',
            'is_verified' => true,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
