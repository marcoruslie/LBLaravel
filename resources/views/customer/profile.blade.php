@extends('layouts.layout')

@section('pagename')
    Profil Pengguna
@endsection

@section('header')
    @include('customer.partial.customerNavbar')
@endsection

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h2>Profil Saya</h2>
        </div>
        <div class="card-body">
            <form id="formEditProfile" method="POST" action="{{ route('edit_profile') }}" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="foto" class="form-label">Foto Profil</label>
                        @if(Session::get('user')->foto)
                            <img src="{{ asset('storage/' . Session::get('user')->foto) }}" class="img-thumbnail mb-2" width="150">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" class="img-thumbnail mb-2" width="150">
                        @endif
                        <input type="file" class="form-control" id="foto" name="foto">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ Session::get('user')->nama }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="{{ Session::get('user')->username }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ Session::get('user')->email }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="no_telepon" class="form-label">No Telepon</label>
                        <input type="text" class="form-control" id="no_telepon" name="no_telepon" value="{{ Session::get('user')->no_telepon }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ Session::get('user')->alamat }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="kota" class="form-label">Kota</label>
                        <input type="text" class="form-control" id="kota" name="kota" value="{{ Session::get('user')->kota }}" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="L" {{ Session::get('user')->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ Session::get('user')->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah password</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
