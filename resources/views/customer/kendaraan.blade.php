@extends('layouts.layout')

@section('pagename')
    Kendaraan
@endsection

@section('header')
    @include('customer.partial.customerNavbar')
@endsection

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between mb-4">
        <h2>Kendaraan Saya</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addKendaraanModal">
            Tambah Kendaraan
        </button>
    </div>
    @if (Session::has('pesan'))
        <div class="alert alert-success">{{ Session::get('pesan') }}</div>
        @php
            Session::forget('pesan');
        @endphp
    @endif
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Tahun</th>
                <th>Plat Nomor</th>
                <th>Merek</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kendaraans as $key => $kendaraan)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $kendaraan->nama }}</td>
                    <td>{{ $kendaraan->tahun }}</td>
                    <td>{{ $kendaraan->plat_nomor }}</td>
                    <td>{{ $kendaraan->merek }}</td>
                    <td>
                        <button type="button" class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal" data-bs-target="#editKendaraanModal"
                                data-id="{{ $kendaraan->id }}"
                                data-nama="{{ $kendaraan->nama }}"
                                data-tahun="{{ $kendaraan->tahun }}"
                                data-plat_nomor="{{ $kendaraan->plat_nomor }}"
                                data-merek="{{ $kendaraan->merek }}">
                            Edit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-bs-toggle="modal" data-bs-target="#deleteKendaraanModal"
                                data-id="{{ $kendaraan->id }}">
                            Hapus
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal to Add New Kendaraan -->
<div class="modal fade" id="addKendaraanModal" tabindex="-1" aria-labelledby="addKendaraanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formTambahKendaraan" method="POST" action="{{ route('tambah_kendaraan') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addKendaraanModalLabel">Tambah Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="tahun" name="tahun" required>
                    </div>
                    <div class="mb-3">
                        <label for="plat_nomor" class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" id="plat_nomor" name="plat_nomor" required>
                    </div>
                    <div class="mb-3">
                        <label for="merek" class="form-label">Merek</label>
                        <input type="text" class="form-control" id="merek" name="merek" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Kendaraan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal to Edit Kendaraan -->
<div class="modal fade" id="editKendaraanModal" tabindex="-1" aria-labelledby="editKendaraanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formEditKendaraan" method="POST" action="{{ route('edit_kendaraan') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editKendaraanModalLabel">Edit Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="edit_nama" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tahun" class="form-label">Tahun</label>
                        <input type="text" class="form-control" id="edit_tahun" name="tahun" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_plat_nomor" class="form-label">Plat Nomor</label>
                        <input type="text" class="form-control" id="edit_plat_nomor" name="plat_nomor" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_merek" class="form-label">Merek</label>
                        <input type="text" class="form-control" id="edit_merek" name="merek" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal to Confirm Delete Kendaraan -->
<div class="modal fade" id="deleteKendaraanModal" tabindex="-1" aria-labelledby="deleteKendaraanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formDeleteKendaraan" method="POST" action="{{ route('delete_kendaraan') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteKendaraanModalLabel">Hapus Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="delete_id" name="id">
                    <p>Apakah Anda yakin ingin menghapus kendaraan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js_script')
<script>
    $(document).ready(function() {
        // Edit button click handler
        $('.btn-edit').on('click', function() {
            var id = $(this).data('id');
            var nama = $(this).data('nama');
            var tahun = $(this).data('tahun');
            var plat_nomor = $(this).data('plat_nomor');
            var merek = $(this).data('merek');

            $('#edit_id').val(id);
            $('#edit_nama').val(nama);
            $('#edit_tahun').val(tahun);
            $('#edit_plat_nomor').val(plat_nomor);
            $('#edit_merek').val(merek);
        });

        // Delete button click handler
        $('.btn-delete').on('click', function() {
            var id = $(this).data('id');
            $('#delete_id').val(id);
        });
    });
</script>
@endsection
