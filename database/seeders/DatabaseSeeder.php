<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'nama' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            KwhCategorySeeder::class, // kategori KWH dulu
            UserSeeder::class,        // user dulu
            CustomerSeeder::class,    // baru customer
            InvoiceSeeder::class,     // baru invoice
        ]);
    }
}
