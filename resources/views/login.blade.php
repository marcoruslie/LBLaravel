@extends('layouts.layoutIndex')
@section('pagename')
    Login
@endsection
@section('content')
    @php
        // $userData = DB::table('users')->get();
        // $tokoData = DB::table('toko')->get();
        // dd($data);
    @endphp
    <div class="row align-items-center m-0 bg-secondary" style="height: 100vh; width: 100vw">
        <div class="col"></div>
        <div class="col bg-dark rounded-2 py-4 px-4 align-items-center">

            @if ($errors->any())
                @foreach ($errors->all() as $pesanError)
                    <div class="alert alert-danger">{{ $pesanError }}</div>
                @endforeach
            @endif
            @if (Session::has('pesan'))
                <div class="alert alert-success">{{ Session::get('pesan') }}</div>
                @php
                    Session::forget('pesan');
                @endphp
            @elseif (Session::has('pesanError'))
                <div class="alert alert-danger">{{ Session::get('pesanError') }}</div>
                @php
                    Session::forget('pesanError');
                @endphp
            @endif
            <div class="row-4 mb-3 text-white text-center fs-1 fw-bold">UD. ASIA MOTOR</div>
            <form action="{{ route('customer_login') }}" method="post">
                @csrf
                <div class="form-floating mb-3">
                    {{-- email --}}
                    <input name="username" type="text" class="form-control" id="floatingInput">
                    <label for="floatingInput">Username</label>
                </div>
                <div class="form-floating">
                    {{-- password --}}
                    <input name="password" type="password" class="form-control" id="floatingPassword">
                    <label for="floatingPassword">Password</label>
                </div>
                <div class="d-flex justify-content-center">
                    <input type="submit" class="btn btn-outline-light mt-2" value="Log in">
                </div>
            </form>
            <div class="d-flex justify-content-center">
                <div class="text-white">
                    still dont have an account?
                    <a href="{{ url('/register') }}">sign up</a>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
@endsection
@section('footer')
    <div class="text-black text-center">© 2024 Asia Motor</div>
@endsection
