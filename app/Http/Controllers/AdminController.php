<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
use App\Models\Aksesoris;
use App\Models\Layanan;
use App\Models\Kategori;
use Illuminate\Http\Request;


class AdminController extends Controller
{

    public function getReservasi(Request $request)
    {
        $keyword = $request->keyword;
        $allReservasi = Reservasi::with(['layanan', 'aksesorisLayanan.aksesoris','pembayaran'])->orderBy('tanggal_reservasi', 'desc')
            ->orderBy('jam_reservasi', 'desc')
            ->get();
        if ($request->keyword != null) {
            $allReservasi = Reservasi::with(['pelanggan', 'layanan', 'aksesorisLayanan.aksesoris'])
                ->where(function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->keyword . '%')
                        ->orWhereHas('pelanggan', function ($subquery) use ($request) {
                            $subquery->where('nama', 'like', '%' . $request->keyword . '%')
                                ->orWhere('no_telepon', 'like', '%' . $request->keyword . '%')
                                ->orWhere('email', 'like', '%' . $request->keyword . '%');
                        })
                        ->orWhere('tanggal_reservasi', 'like', '%' . $request->keyword . '%')
                        ->orWhere('jam_reservasi', 'like', '%' . $request->keyword . '%')
                        ->orWhere('status_reservasi', 'like', '%' . $request->keyword . '%');
                })
                ->orderBy('tanggal_reservasi', 'desc')
                ->orderBy('jam_reservasi', 'desc')
                ->get();
        }
        $currPage = 'reservasi';
        return view('admin.reservasi', compact('currPage', 'allReservasi', 'keyword'));
    }
    public function getlayanan(Request $request)
    {
        $currPage = 'layanan';
        $allLayanan = Layanan::all();
        $keyword = $request->keyword;
        if ($request->keyword != null) {
            $allLayanan = Layanan::where('nama_layanan', 'like', '%' . $request->keyword . '%')
                ->orWhere('harga', 'like', '%' . $request->keyword . '%')
                ->get();
        }
        return view('admin.layanan', compact('currPage', 'allLayanan', 'keyword'));
    }
    public function getAksesoris(Request $request)
    {
        $currPage = 'aksesoris';
        $allAksesoris = Aksesoris::all();
        $allKategori = Kategori::all();
        $keyword = $request->keyword;
        if ($request->keyword != null) {
            $allAksesoris = Aksesoris::where('nama', 'like', '%' . $keyword . '%')
                ->orWhere('harga', 'like', '%' . $keyword . '%')
                ->orWhereHas('kategori', function ($query) use ($keyword) {
                    $query->where('nama', 'like', '%' . $keyword . '%');
                })
                ->get();
        }
        return view('admin.aksesoris', compact('currPage', 'allKategori', 'allAksesoris', 'keyword'));
    }

    public function updateStatus(Request $request)
    {
        $reservasi = Reservasi::find($request->reservasi_id);
        $reservasi->status_reservasi = $request->status;
        $reservasi->save();

        return redirect()->back();
    }

    public function searchReservasi(Request $request)
    {
        $keyword = $request->search;

        return redirect()->route('admin_dashboard', compact('keyword'));
    }

    public function addAksesoris(Request $request)
    {
        $aksesoris = new Aksesoris();
        $aksesoris->nama = $request->nama;
        $aksesoris->harga = $request->harga;
        $aksesoris->stok = $request->stok;
        $aksesoris->kategori_id = $request->kategori_id;
        $aksesoris->save();

        return redirect()->back();
    }

    public function searchAksesoris(Request $request)
    {
        $keyword = $request->search;

        return redirect()->route('admin_aksesoris', compact('keyword'));
    }

    public function updateStok(Request $request)
    {
        $aksesoris = Aksesoris::find($request->aksesoris_id);
        $aksesoris->stok += $request->stok;
        $aksesoris->save();

        return redirect()->back();
    }

    public function deleteAksesoris(Request $request)
    {
        $aksesoris = Aksesoris::find($request->aksesoris_id);
        $aksesoris->delete();

        return redirect()->back();
    }

    public function searchLayanan(Request $request)
    {
        $keyword = $request->search;

        return redirect()->route('admin_layanan', compact('keyword'));
    }

    public function deleteLayanan(Request $request)
    {
        $layanan = Layanan::find($request->layanan_id);
        $layanan->delete();

        return redirect()->back();
    }

    public function updateLayanan(Request $request)
    {
        $layanan = Layanan::find($request->layanan_id);
        $layanan->nama_layanan = $request->nama_layanan;
        $layanan->harga = $request->harga;
        $layanan->save();

        return redirect()->back();
    }

    public function addLayanan(Request $request)
    {
        $layanan = new Layanan();
        $layanan->nama_layanan = $request->nama_layanan;
        $layanan->harga = $request->harga;
        $layanan->durasi_layanan = $request->durasi_layanan;
        $layanan->save();

        return redirect()->back();
    }
}
