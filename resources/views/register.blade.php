@extends('layouts.layoutIndex')
@section('pagename')
    Register
@endsection
@section('content')
<div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
    <div class="bg-dark rounded-4 py-4 px-4 align-items-center overflow-auto w-50" style="max-height: 100%">
        @if ($errors->any())
            @foreach ($errors->all() as $pesanError)
                <div class="alert alert-danger">{{ $pesanError }}</div>
            @endforeach
        @endif
        <form action="{{ route('customer_register') }}" method="post">
            @csrf
            <div class="row-4 mb-3 text-white text-center fs-1 fw-bold">REGISTER FORM</div>
            <div class="form-floating mb-2">
                <input name="nama" type="text" class="form-control" id="floatingNama" placeholder="Nama Lengkap" value="{{ old('nama') }}" required>
                <label for="floatingNama">Nama Lengkap</label>
            </div>
            <div class="form-floating mb-2">
                <input name="username" type="text" class="form-control" id="floatingUsername" placeholder="Username" value="{{ old('username') }}" required>
                <label for="floatingUsername">Username</label>
            </div>
            <div class="form-floating mb-2">
                <input name="alamat" type="text" class="form-control" id="floatingAlamat" placeholder="Alamat" value="{{ old('alamat') }}" required>
                <label for="floatingAlamat">Alamat</label>
            </div>
            <div class="form-floating mb-2">
                <input name="no_telepon" type="text" class="form-control" id="floatingNoTelepon" placeholder="Nomor Telepon" value="{{ old('no_telepon') }}" required>
                <label for="floatingNoTelepon">Nomor Telepon</label>
            </div>
            <div class="form-floating mb-2">
                <input name="email" type="email" class="form-control" id="floatingEmail" placeholder="Email" value="{{ old('email') }}" required>
                <label for="floatingEmail">Email</label>
            </div>
            <div class="form-floating mb-2">
                <input name="kota" type="text" class="form-control" id="floatingKota" placeholder="Kota" value="{{ old('kota') }}" required>
                <label for="floatingKota">Kota</label>
            </div>
            <div class="form-floating mb-2">
                <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password" required>
                <label for="floatingPassword">Password</label>
            </div>
            <div class="form-floating mb-2">
                <input name="password_confirmation" type="password" class="form-control" id="floatingPasswordConfirm" placeholder="Confirm Password" required>
                <label for="floatingPasswordConfirm">Confirm Password</label>
            </div>
            <div class="text-white mb-2">
                Jenis Kelamin:
                <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required> Perempuan
                <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required> Laki - Laki
            </div>
            <div class="row">
                <div class="col"></div>
                <div class="col d-flex justify-content-center">
                    <input type="submit" class="btn btn-outline-light mt-2" value="REGISTER">
                </div>
                <div class="col"></div>
            </div>
            <div class="text-white text-center">
                Already have an account? <a href="/">sign in</a>
            </div>
        </form>
    </div>
</div>


@endsection
@section('footer')
<div class="text-black text-center">© 2024 Asia Motor</div>
@endsection

