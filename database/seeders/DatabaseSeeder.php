<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil CategorySeeder untuk mengisi data master kategori pernikahan
        $this->call([
            CategorySeeder::class,
            // Jika nanti ada VendorProfileSeeder atau UserSeeder, Anda tinggal menambahkannya di sini
        ]);
    }
}