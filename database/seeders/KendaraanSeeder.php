<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kendaraan;
use App\Models\Pelanggan;
use Faker\Factory as Faker;

class KendaraanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        // Define pelanggan_ids to choose from
        $pelangganIds = Pelanggan::pluck('id')->toArray();

        for ($i = 0; $i < 7; $i++) {
            Kendaraan::create([
                'nama' => $faker->word,
                'tahun' => $faker->year,
                'merek' => $faker->company,
                'plat_nomor' => strtoupper($faker->bothify('?? #### ??')),
                'pelanggan_id' => $faker->randomElement($pelangganIds),
            ]);
        }
    }
}
