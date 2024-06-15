<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Layanan;
use App\Models\Aksesoris;
use App\Models\Kategori;
use Faker\Factory as Faker;


class LayananSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        $categories = ['Interior', 'Eksterior', 'Mesin', 'Elektronik'];

        foreach ($categories as $category) {
            Kategori::create(['nama' => $category]);
        }

        $kategoriIds = Kategori::pluck('id')->toArray();

        for ($i = 0; $i < 10; $i++) {
            Aksesoris::create([
                'nama' => $faker->word,
                'harga' => $faker->numberBetween(10000, 200000),
                'stok' => $faker->numberBetween(1, 100),
                'kategori_id' => $faker->randomElement($kategoriIds),
            ]);
        }

        Layanan::create([
            'nama_layanan' => 'Cuci Mobil',
            'harga' => 50000,
            'durasi_layanan' => $faker->randomElement($kategoriIds),
        ]);
    }
}
