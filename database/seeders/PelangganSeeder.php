<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class PelangganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        DB::table('pelanggan')->delete();
        for ($i = 0; $i < 5; $i++) {
            Pelanggan::create([
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_telepon' => $faker->phoneNumber,
                'username' => $faker->unique()->userName,
                'password' => '123', // Use a default password or $faker->password
                'kota' => $faker->city,
                'jenis_kelamin' => $faker->randomElement(['L', 'P']),
                'email' => $faker->unique()->safeEmail,
            ]);
        }
    }
}
