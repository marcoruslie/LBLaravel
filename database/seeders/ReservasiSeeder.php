<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AksesorisLayanan;
use Illuminate\Database\Seeder;
use App\Models\Reservasi;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\Layanan;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\Pembayaran;

class ReservasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // Get all pelanggan_ids, kendaraan_ids, and layanan_ids
        $pelangganIds = Pelanggan::pluck('id')->toArray();
        $kendaraanIds = Kendaraan::pluck('id')->toArray();
        $layananIds = Layanan::pluck('id')->toArray();
        Admin::create([
            'username' => 'admin',
            'password' => '123',
        ]);
        for ($i = 0; $i < 15; $i++) {
            $tanggal_reservasi = $faker->dateTimeBetween('2024-01-01', '2024-06-01')->format('Y-m-d');
            $jam_reservasi = $faker->time;
            $keterangan = $faker->text(40);
            $status_reservasi = $faker->randomElement(['Menunggu Konfirmasi', 'Dikerjakan', 'Ditolak', 'Selesai']);
            $pelanggan_id = $faker->randomElement($pelangganIds);
            $kendaraan_id = $faker->randomElement($kendaraanIds);
            $layanan_id = $faker->randomElement($layananIds);

            Reservasi::create([
                'tanggal_reservasi' => $tanggal_reservasi,
                'jam_reservasi' => $jam_reservasi,
                'keterangan' => $keterangan,
                'status_reservasi' => $status_reservasi,
                'jenis_aksesoris' => $faker->randomElement(['Bawa Sendiri', 'Beli Ditempat']),
                'pelanggan_id' => $pelanggan_id,
                'kendaraan_id' => $kendaraan_id,
                'layanan_id' => $layanan_id,
            ]);
        }

        $reservasiIds = Reservasi::all()->pluck('id')->toArray();
        for ($i = 0; $i < 25; $i++) {

            AksesorisLayanan::create([
                'reservasi_id' => $faker->randomElement($reservasiIds),
                'aksesoris_id' => $faker->numberBetween(1, 10),
                'jumlah' => $faker->numberBetween(1, 5),
            ]);
        }
        foreach (Reservasi::all() as $reservasi) {
            if($reservasi->status_reservasi == 'Selesai' || $reservasi->status_reservasi == 'Dikerjakan' || $reservasi->status_reservasi == 'Menunggu Konfirmasi'){
                $total = 0;
                foreach ($reservasi->aksesorisLayanan as $aksesorisLayanan) {
                    $total += $aksesorisLayanan->aksesoris->harga * $aksesorisLayanan->jumlah + $reservasi->layanan->harga;
                }
                Pembayaran::create([
                    'no_invoice' => Str::random(45),
                    'admin_id' => 1,
                    'reservasi_id' => $reservasi->id,
                    'total' => $total,
                    'metode_pembayaran' => $faker->randomElement(['cash','transfer']),
                    'status' => $faker->randomElement(['pending','success','cancel']),
                ]);
            }
        }
    }
}
