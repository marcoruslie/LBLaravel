@extends('layouts.layout')
@section('pagename')
    Layanan Servis
@endsection
@section('header')
    @include('admin.partial.adminNavbar')
@endsection
@section('content')
    <div class="w-75">
        <form method="POST" action="{{ route('search_layanan') }}" class="d-flex mb-1 column-gap-2">
            @csrf
            <input name="search" class="w-50 class p-2 rounded" type="text" placeholder="Search..."
                value="{{ $keyword }}">
            <button type="submit" class="btn btn-secondary rounded w-25">Search</button>
            <button type="button" class="btn btn-light rounded w-25" data-bs-toggle="modal" data-bs-target="#addModal">Tambah</button>
        </form>
        <table class="table rounded border table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Layanan</th>
                    <th scope="col">Harga</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allLayanan as $key => $layanan)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $layanan->nama_layanan }}</td>
                        <td class="harga">{{ $layanan->harga }}</td>
                        <td class="d-flex column-gap-2">
                            <form method="POST" action="{{ route('delete_layanan') }}">
                                @csrf
                                <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editModal"
                                onclick="editLayanan({{ $layanan->id }}, '{{ $layanan->nama_layanan }}',{{ $layanan->harga }}, {{ $layanan->durasi_layanan }})">Edit</button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
    <!-- Modal Update Layanan-->
    <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('update_layanan') }}">
                    @csrf
                    <div class="modal-body" id="modal-body">
                        <input type="hidden" name="layanan_id" id="layanan_id">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="nama_layanan" name="nama_layanan">
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi Layanan</label>
                            <input type="number" class="form-control" id="durasi_layanan" name="durasi_layanan">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Add Layanan-->
    <div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('add_layanan') }}">
                    @csrf
                    <div class="modal-body" id="modal-body">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Layanan</label>
                            <input type="text" class="form-control" id="nama" name="nama_layanan">
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga">
                        </div>
                        <div class="mb-3">
                            <label for="durasi_layanan" class="form-label">Durasi Layanan</label>
                            <input type="number" class="form-control" id="durasi" name="durasi_layanan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah Layanan</button>
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

        function editLayanan(id, nama, harga, durasi) {
            // Set the values of input fields in the modal
            document.getElementById('layanan_id').value = id;
            document.getElementById('nama_layanan').value = nama;
            document.getElementById('harga').value = harga;
            document.getElementById('durasi_layanan').value = durasi;
        }
    </script>
@endsection
