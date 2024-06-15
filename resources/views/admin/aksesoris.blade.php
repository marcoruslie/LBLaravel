@extends('layouts.layout')
@section('pagename')
    Aksesoris
@endsection
@section('header')
    @include('admin.partial.adminNavbar')
@endsection
@section('content')
    <div class="w-75">
        <form method="POST" action="{{ route('search_aksesoris') }}" class="d-flex mb-1 column-gap-2">
            @csrf
            <input name="search" class="w-50 class p-2 rounded" type="text" placeholder="Search..."
                value="{{ $keyword }}">
            <button type="submit" class="btn btn-secondary rounded w-25">Cari</button>
            <button type="button" class="btn btn-light rounded w-25" data-bs-toggle="modal" data-bs-target="#addAksesorisModal">Tambah</button>
        </form>
        <table class="table rounded border table-striped">
            <thead>
                <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">Nama Aksesoris</th>
                    <th class="text-center" scope="col">Harga</th>
                    <th class="text-center" scope="col">Stok</th>
                    <th class="text-center" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allAksesoris as $key => $aksesoris)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td class="text-center">{{ $aksesoris->nama }}</td>
                        <td class="text-center harga">{{ $aksesoris->harga }}</td>
                        <td class="text-center">{{ $aksesoris->stok }}</td>
                        <td class="d-flex column-gap-2 justify-content-center align-item-center">
                            <form method="POST" action="{{ route('update_stok') }}" class="d-flex column-gap-2">
                                @csrf
                                <input type="hidden" name="aksesoris_id" value="{{ $aksesoris->id }}"
                                    class="visually-hidden">
                                <input type="number" name="stok" class="form" placeholder="Tambah Stok">
                                <button type="submit" class="btn btn-primary">Update Stok</button>
                            </form>
                            <form method="POST" action="{{ route('delete_aksesoris') }}" class="d-flex">
                                @csrf
                                <input type="hidden" name="aksesoris_id" value="{{ $aksesoris->id }}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Add Aksesoris -->
    <div class="modal fade" id="addAksesorisModal" tabindex="-1" aria-labelledby="addAksesorisModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAksesorisModalLabel">Tambah Aksesoris</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add_aksesoris') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Aksesoris</label>
                            <input type="text" class="form-control" id="nama" name="nama">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga">
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok">
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori_id">
                                @foreach($allKategori as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Aksesoris</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('footer')
    @include('admin.partial.adminFooter')
@endsection

@section('js_script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function formatRupiah(number) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(number);
            }

            $('.harga').each(function() {
                var harga = $(this).text();
                var formattedHarga = formatRupiah(harga);
                $(this).text(formattedHarga);
            });
        });
    </script>
@endsection
