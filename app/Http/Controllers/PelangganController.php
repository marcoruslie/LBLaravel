<?php

namespace App\Http\Controllers;

use App\Models\Aksesoris;
use App\Models\AksesorisLayanan;
use App\Models\Kendaraan;
use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Reservasi;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PelangganController extends Controller
{
    public function getReservasiPelanggan(Request $request)
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('pesanError', 'Anda harus login terlebih dahulu');
        }
        $user = Session::get('user');
        $reservasis = Reservasi::with('kendaraan', 'layanan', 'aksesorisLayanan.aksesoris', 'pembayaran')->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_reservasi', 'desc')->where('pelanggan_id', $user->id)->get();
        if ($request->keyword != null) {
            $reservasis = Reservasi::with('kendaraan', 'layanan')
                ->where('pelanggan_id', $user->id)
                ->where('status', 'like', '%' . $request->keyword . '%')
                ->orWhere('kendaraan.nama', 'like', '%' . $request->keyword . '%')
                ->orWhere('layanan.nama', 'like', '%' . $request->keyword . '%')
                ->get();
        }
        $aksesoris = Aksesoris::all();
        $kendaraans = Pelanggan::find($user->id)->kendaraan;
        $layanans = Layanan::all();
        $keyword = $request->keyword;
        return view('customer.reservasi', compact('reservasis', 'keyword', 'kendaraans', 'aksesoris', 'layanans'));
    }
    public function getKendaraanPelanggan()
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('pesanError', 'Anda harus login terlebih dahulu');
        }
        $kendaraans = Kendaraan::where('pelanggan_id', Session::get('user')->id)->get();
        return view('customer.kendaraan', compact('kendaraans'));
    }

    public function getProfilePelanggan()
    {
        if (!Session::has('user')) {
            return redirect()->route('login')->with('pesanError', 'Anda harus login terlebih dahulu');
        }
        return view('customer.profile');
    }

    public function loginUser(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        if($request->username == 'admin' && $request->password == 'admin'){
            return redirect()->route('admin_dashboard');
        }
        // Retrieve the user from the database
        $user = DB::table('pelanggan')->where('username', $request->username)->first();

        // Check if user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user information in session
            Session::put('user', $user);
            // Redirect to the customer reservation route
            return redirect()->route('customer_reservasi');
        } else {

            return back()->with('pesanError', 'Invalid credentials');
        }
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:pelanggan,username', // Ensure username is unique
            'password' => 'required|string|min:8|confirmed', // Password confirmation
            'email' => 'required|email|max:255|unique:pelanggan,email', // Ensure email is unique
            'no_telepon' => 'required|string|max:20', // Adjusted to match your schema
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P', // Ensure it matches either 'L' or 'P'
        ]);

        // Create a new instance of the Pelanggan model
        $pelanggan = new Pelanggan();
        $pelanggan->nama = $request->nama;
        $pelanggan->username = $request->username;
        // Hash the password before saving it
        $pelanggan->password = bcrypt($request->password);
        $pelanggan->email = $request->email;
        $pelanggan->no_telepon = $request->no_telepon;
        $pelanggan->alamat = $request->alamat;
        $pelanggan->kota = $request->kota;
        $pelanggan->jenis_kelamin = $request->jenis_kelamin;

        // Save the new Pelanggan record to the database
        $pelanggan->save();

        // Redirect to the login route with a success message
        return redirect()->route('login')->with('pesan', 'Registrasi berhasil, silahkan login');
    }

    public function addReservasi(Request $request)
    {
        $request->validate([
            'tanggal_reservasi' => 'required|date',
            'kendaraan_id' => 'required|integer',
            'layanan_id' => 'required|integer',
            'aksesorisData' => 'nullable|string',
        ]);
        $pelanggan = Session::get('user');
        $pelanggan_id = $pelanggan->id; // Assuming the user is authenticated

        // Parse the datetime-local input into separate date and time
        $tanggal_reservasi = date('Y-m-d', strtotime($request->tanggal_reservasi));
        $jam_reservasi = date('H:i:s', strtotime($request->tanggal_reservasi));
        $jenis_aksesoris = 'Bawa Sendiri';
        if ($request->has('aksesoris_id')) {
            $jenis_aksesoris = 'Beli Ditempat';
        }
        // Create new reservasi
        $reservasi = Reservasi::create([
            'tanggal_reservasi' => $tanggal_reservasi,
            'jam_reservasi' => $jam_reservasi,
            'keterangan' => '',
            'status_reservasi' => 'Menunggu Konfirmasi',
            'jenis_aksesoris' => $jenis_aksesoris,
            'layanan_id' => $request->layanan_id,
            'pelanggan_id' => $pelanggan_id,
            'kendaraan_id' => $request->kendaraan_id,
        ]);

        // Process aksesoris
        if ($request->has('aksesoris_id')) {
            foreach ($request->aksesoris_id as $aksesoris) {
                list($aksesoris_id, $jumlah) = explode(',', $aksesoris);
                AksesorisLayanan::create([
                    'aksesoris_id' => $aksesoris_id,
                    'reservasi_id' => $reservasi->id,
                    'jumlah' => $jumlah,
                ]);
            }
        }

        return redirect()->back()->with('pesan', 'Reservasi berhasil ditambahkan');
    }

    public function addKendaraan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:45',
            'tahun' => 'required|string|max:45',
            'plat_nomor' => 'required|string|max:45',
            'merek' => 'required|string|max:45',
        ]);

        $pelanggan_id = Session::get('user')->id;

        Kendaraan::create([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'plat_nomor' => $request->plat_nomor,
            'merek' => $request->merek,
            'pelanggan_id' => $pelanggan_id,
        ]);

        return redirect()->route('customer_kendaraan')->with('pesan', 'Kendaraan berhasil ditambahkan');
    }
    public function editKendaraan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kendaraan,id',
            'nama' => 'required|string|max:45',
            'tahun' => 'required|string|max:45',
            'plat_nomor' => 'required|string|max:45',
            'merek' => 'required|string|max:45',
        ]);

        $kendaraan = Kendaraan::find($request->id);
        $kendaraan->update([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'plat_nomor' => $request->plat_nomor,
            'merek' => $request->merek,
        ]);

        return redirect()->route('customer_kendaraan')->with('pesan', 'Kendaraan berhasil diperbarui');
    }

    public function deleteKendaraan(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kendaraan,id',
        ]);

        $kendaraan = Kendaraan::find($request->id);
        $kendaraan->delete();

        return redirect()->route('customer_kendaraan')->with('pesan', 'Kendaraan berhasil dihapus');
    }

    public function editProfile(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'no_telepon' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'kota' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'password' => 'nullable|string|min:8|confirmed',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Pelanggan::find(Session::get('user')->id);

        $user->nama = $request->nama;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->no_telepon = $request->no_telepon;
        $user->alamat = $request->alamat;
        $user->kota = $request->kota;
        $user->jenis_kelamin = $request->jenis_kelamin;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            $path = $request->file('foto')->store('profile_pictures', 'public');
            $user->foto = $path;
        }

        $user->save();
        Session::put('user', $user);

        return redirect()->route('customer_profile')->with('pesan', 'Profil berhasil diperbarui');
    }

    public function addPembayaran(Request $request)
    {
        // Validate the request
        $request->validate([
            'reservasi_id' => 'required|exists:reservasi,id',
            'total' => 'required|numeric',
            'metode_pembayaran' => 'required|string',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Calculate the total accessories price
        $totalAksesoris = 0;
        if ($request->has('aksesoris_id')) {
            foreach ($request->aksesoris_id as $aksesoris) {
                list($aksesorisId, $jumlah) = explode(',', $aksesoris);
                $aksesorisHarga = Aksesoris::find($aksesorisId)->harga;
                $totalAksesoris += $aksesorisHarga * $jumlah;
            }
        }

        // Handle file upload for bukti_pembayaran if metode_pembayaran is not cash
        if ($request->metode_pembayaran == 'transfer' && $request->hasFile('bukti_pembayaran')) {
            $fileName = time() . '.' . $request->bukti_pembayaran->extension();
            $request->bukti_pembayaran->storeAs('public/bukti_pembayaran', $fileName);
            $fileName = 'bukti_pembayaran/' . $fileName;
        } else {
            $fileName = null;
        }

        // Create the pembayaran record
        $pembayaran = new Pembayaran();
        $pembayaran->reservasi_id = $request->reservasi_id;
        $pembayaran->total = $request->total + $totalAksesoris;
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->bukti_pembayaran = $fileName;
        $pembayaran->status = 'success';  // Assuming payment status starts as pending
        $pembayaran->admin_id = 1; // Assuming admin id is always 1
        $pembayaran->no_invoice = 'INV' . time(); // Generate invoice number
        $pembayaran->save();

        // Update the reservasi status to 'Paid'
        $reservasi = Reservasi::find($request->reservasi_id);
        $reservasi->status_reservasi = 'Menunggu Konfirmasi';
        $reservasi->save();

        // Redirect back with a success message
        return redirect()->back()->with('pesan', 'Pembayaran berhasil dilakukan.');
    }

    public function logoutUser()
    {
        Session::forget('user');
        return redirect()->route('login');
    }
}
