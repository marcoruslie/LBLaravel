<?php

namespace Database\Seeders;

use App\Models\Aksesoris;
use App\Models\Pelanggan;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            PelangganSeeder::class,
            KendaraanSeeder::class,
            LayananSeeder::class,
            ReservasiSeeder::class,
        ]);
    }
}
