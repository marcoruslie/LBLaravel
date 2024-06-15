<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PelangganController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
})->name('login');
Route::get('register', function () {
    return view('register');
})->name('register');
Route::prefix('customer')->group(function () {
    Route::get('/', [PelangganController::class,'getReservasiPelanggan'])->name('customer_reservasi');
    Route::get('/profile', [PelangganController::class,'getProfilePelanggan'])->name('customer_profile');
    Route::get('/kendaraan', [PelangganController::class,'getKendaraanPelanggan'])->name('customer_kendaraan');

    Route::get('/logout', [PelangganController::class,'logoutUser'])->name('customer_logout');

    Route::post('/login', [PelangganController::class,'loginUser'])->name('customer_login');
    Route::post('/register', [PelangganController::class,'registerUser'])->name('customer_register');
    Route::post('/addReservasi', [PelangganController::class,'addReservasi'])->name('tambah_reservasi_pelanggan');
    Route::post('/addKendaraan', [PelangganController::class,'addKendaraan'])->name('tambah_kendaraan');
    Route::post('/addPembayaran',[PelangganController::class,'addPembayaran'])->name('tambah_pembayaran');

    Route::post('/deleteReservasi', [PelangganController::class,'deleteReservasi'])->name('delete_reservasi');
    Route::post('/deleteKendaraan', [PelangganController::class,'deleteKendaraan'])->name('delete_kendaraan');

    Route::post('/editReservasi', [PelangganController::class,'editReservasi'])->name('edit_reservasi');
    Route::post('/editKendaraan', [PelangganController::class,'editKendaraan'])->name('edit_kendaraan');
    Route::post('/editProfile', [PelangganController::class,'editProfile'])->name('edit_profile');
});
Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class,'getReservasi'])->name('admin_dashboard');
    Route::get('/pembayaran', [AdminController::class,'getPembayaran'])->name('admin_pembayaran');
    Route::get('/layanan',[AdminController::class,'getLayanan'])->name('admin_layanan');
    Route::get('/aksesoris', [AdminController::class,'getAksesoris'])->name('admin_aksesoris');

    Route::post('/add-aksesoris', [AdminController::class,'addAksesoris'])->name('add_aksesoris');
    Route::post('/add-layanan', [AdminController::class,'addLayanan'])->name('add_layanan');


    Route::post('/update-status', [AdminController::class,'updateStatus'])->name('update_status');
    Route::post('/update-stok', [AdminController::class,'updateStok'])->name('update_stok');
    Route::post('/update-layanan', [AdminController::class,'updateLayanan'])->name('update_layanan');
    // Route::post('/update-pembayaran', [AdminController::class,'updatePembayaran'])->name('update_pembayaran');
    // Route::post('/update-layanan', [AdminController::class,'updateLayanan'])->name('update_layanan');
    // Route::post('/update-aksesoris', [AdminController::class,'updateAksesoris'])->name('update_aksesoris');

    Route::post('/search-reservasi', [AdminController::class,'searchReservasi'])->name('search_reservasi');
    Route::post('/search-aksesoris', [AdminController::class,'searchAksesoris'])->name('search_aksesoris');
    Route::post('/search-layanan', [AdminController::class,'searchLayanan'])->name('search_layanan');


    Route::post('/delete-aksesoris', [AdminController::class,'deleteAksesoris'])->name('delete_aksesoris');
    Route::post('/delete-layanan', [AdminController::class,'deleteLayanan'])->name('delete_layanan');
});
