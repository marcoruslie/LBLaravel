@extends('layouts.layout')
@section('pagename')
    Customer Reservasi
@endsection
@section('header')
    @include('customer.partial.customerNavbar')
@endsection
@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Reservasi Saya</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReservasiModal">
                Tambah Reservasi
            </button>
        </div>
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
        @endif
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal Reservasi</th>
                    <th>Nama Kendaraan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservasis as $key => $reservasi)
                    <tr class="status-{{ strtolower($reservasi->status_reservasi) }}">
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $reservasi->tanggal_reservasi }}</td>
                        <td>{{ $reservasi->kendaraan->nama }}</td>
                        <td>{{ $reservasi->status_reservasi }}</td>
                        <td>
                            <button type="button" class="btn btn-info btn-detail" data-bs-toggle="modal"
                                data-bs-target="#detailReservasiModal" data-tanggal="{{ $reservasi->tanggal_reservasi }}"
                                data-jam="{{ $reservasi->jam_reservasi }}" data-keterangan="{{ $reservasi->keterangan }}"
                                data-status="{{ $reservasi->status_reservasi }}"
                                data-layanan="{{ $reservasi->layanan->nama_layanan }}"
                                data-harga="{{ $reservasi->layanan->harga }}"
                                data-durasi="{{ $reservasi->layanan->durasi_layanan }}"
                                data-nama="{{ $reservasi->kendaraan->nama }}"
                                data-tahun="{{ $reservasi->kendaraan->tahun }}"
                                data-plat="{{ $reservasi->kendaraan->plat_nomor }}"
                                data-merek="{{ $reservasi->kendaraan->merek }}"
                                data-listAksesoris="{{ json_encode($reservasi->aksesorisLayanan->toArray()) }}">
                                Detail
                            </button>
                            @if (is_null($reservasi->pembayaran))
                                <button type="button" class="btn btn-success btn-pembayaran" data-bs-toggle="modal"
                                    data-bs-target="#pembayaranReservasiModal" data-reservasi-id="{{ $reservasi->id }}"
                                    data-layanan-harga="{{ $reservasi->layanan->harga }}"
                                    data-list-aksesoris="{{ $reservasi->aksesorisLayanan }}">
                                    Pembayaran
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal to Add New Reservasi -->
    <div class="modal fade" id="addReservasiModal" tabindex="-1" aria-labelledby="addReservasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formTambahReservasi" method="POST" action="{{ route('tambah_reservasi_pelanggan') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReservasiModalLabel">Tambah Reservasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tanggal_reservasi" class="form-label">Tanggal Reservasi</label>
                            <input type="datetime-local" class="form-control" id="tanggal_reservasi"
                                name="tanggal_reservasi" required>
                        </div>
                        <div class="mb-3">
                            <label for="kendaraan_id" class="form-label">Pilih Kendaraan</label>
                            <select class="form-select" id="kendaraan_id" name="kendaraan_id" required>
                                <option value="" disabled selected>Pilih Kendaraan</option>
                                @foreach ($kendaraans as $kendaraan)
                                    <option value="{{ $kendaraan->id }}">{{ $kendaraan->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="layanan_id" class="form-label">Pilih Layanan</label>
                            <select class="form-select" id="layanan_id" name="layanan_id" required>
                                <option value="" disabled selected>Pilih Layanan</option>
                                @foreach ($layanans as $layanan)
                                    <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="aksesoris" class="form-label">Pilih Aksesoris</label>
                            <select class="form-select" id="aksesoris" multiple>
                                <option value="" disabled>Pilih Aksesoris</option>
                                @foreach ($aksesoris as $item)
                                    <option value="{{ $item->id }}" data-aksesoris-id="{{ $item->id }}">
                                        {{ $item->nama }} (Harga: {{ $item->harga }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah</label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah">
                        </div>
                        <button type="button" class="btn btn-primary" id="add-item-btn">Tambah Item</button>
                        <table class="table mt-3">
                            <thead>
                                <tr>
                                    <th>Nama Aksesoris</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="item-list"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Tambah Reservasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal to Show Reservasi Details -->
    <div class="modal fade" id="detailReservasiModal" tabindex="-1" aria-labelledby="detailReservasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailReservasiModalLabel">Detail Reservasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Tanggal Reservasi:</strong> <span id="detailTanggal"></span></p>
                    <p><strong>Jam Reservasi:</strong> <span id="detailJam"></span></p>
                    <p><strong>Keterangan:</strong> <span id="detailKeterangan"></span></p>
                    <p><strong>Status Reservasi:</strong> <span id="detailStatus"></span></p>
                    <p><strong>Kendaraan:</strong></p>
                    <ul>
                        <li><strong>Nama:</strong> <span id="detailNama"></span></li>
                        <li><strong>Merek:</strong> <span id="detailMerek"></span></li>
                        <li><strong>Tahun:</strong> <span id="detailTahun"></span></li>
                        <li><strong>Plat Nomor:</strong> <span id="detailPlat"></span></li>
                    </ul>
                    <p><strong>Layanan:</strong></p>
                    <ul>
                        <li><strong>Nama Layanan:</strong> <span id="detailLayanan"></span></li>
                        <li><strong>Harga:</strong> <span id="detailHarga"></span></li>
                        <li><strong>Durasi:</strong> <span id="detailDurasi"></span> Menit</li>
                    </ul>
                    <p><strong>Aksesoris Layanan:</strong></p>
                    <ul id="detailListAksesoris">
                        <!-- Aksesoris list will be populated by JS -->
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to Handle Pembayaran -->
    <div class="modal fade" id="pembayaranReservasiModal" tabindex="-1" aria-labelledby="pembayaranReservasiModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="formPembayaranReservasi" method="POST" action="{{ route('tambah_pembayaran') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="pembayaranReservasiModalLabel">Pembayaran Reservasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="reservasi_id" id="reservasi_id">
                        <div class="mb-3">
                            <label for="total" class="form-label">Total Pembayaran</label>
                            <input type="text" class="form-control" id="total" name="" readonly>
                            <input type="hidden" name="total">
                        </div>
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="cash">Cash</option>
                                <option value="transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="mb-3" id="buktiPembayaranWrapper">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Bayar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js_script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Change background color based on status
            $('.Selesai').css('background-color', '#d4edda'); // Green for accepted
            $('.Dikerjakan').css('background-color', '#fff3cd'); // Yellow for pending
            $('.Ditolak').css('background-color', '#f8d7da'); // Red for rejected

            // Handle detail button click
            $('.btn-detail').on('click', function() {
                var modal = $('#detailReservasiModal');
                modal.find('#detailTanggal').text($(this).data('tanggal'));
                modal.find('#detailJam').text($(this).data('jam'));
                modal.find('#detailKeterangan').text($(this).data('keterangan'));
                modal.find('#detailStatus').text($(this).data('status'));
                modal.find('#detailNama').text($(this).data('nama'));
                modal.find('#detailMerek').text($(this).data('merek'));
                modal.find('#detailTahun').text($(this).data('tahun'));
                modal.find('#detailPlat').text($(this).data('plat'));
                modal.find('#detailLayanan').text($(this).data('layanan'));
                modal.find('#detailHarga').text($(this).data('harga'));
                modal.find('#detailDurasi').text($(this).data('durasi'));

                // Handle Aksesoris Layanan list
                var aksesorisList = $(this).data('listaksesoris');
                var aksesorisListElement = modal.find('#detailListAksesoris');
                aksesorisListElement.empty(); // Clear existing list
                if (aksesorisList && aksesorisList.length > 0) {
                    aksesorisList.forEach(function(aksesoris) {
                        aksesorisListElement.append('<li><strong>Nama Aksesoris:</strong> ' +
                            aksesoris.aksesoris.nama + '<br><strong>Harga:</strong> ' +
                            aksesoris.aksesoris.harga +
                            '<br><strong>Jumlah:</strong> ' + aksesoris.jumlah +
                            '</li>');
                    });
                } else {
                    aksesorisListElement.append('<li>No Aksesoris</li>');
                }
            });

            // Handle add item button click
            $('#add-item-btn').on('click', function() {
                var selectedAksesoris = $('#aksesoris').find('option:selected');
                var jumlah = $('#jumlah').val();
                if (!jumlah) {
                    alert('Jumlah tidak boleh kosong');
                    return;
                }
                var itemList = $('#item-list');
                selectedAksesoris.each(function() {
                    var nama = $(this).text().split(' (Harga: ')[0];
                    var harga = $(this).text().split(' (Harga: ')[1].replace(')', '');
                    var aksesorisId = $(this).data(
                        'aksesoris-id'); // Retrieve aksesoris_id from data attribute

                    var row = '<tr>' +
                        '<td>' + nama + '</td>' +
                        '<td>' + formatRupiah(harga) + '</td>' +
                        '<td>' + jumlah + '</td>' +
                        '<td>' +
                        '<input type="hidden" name="aksesoris_id[]" value="' + aksesorisId + ',' +
                        jumlah + '">' +
                        // Hidden input to store aksesoris_id and jumlah
                        '<button type="button" class="btn btn-danger btn-sm delete-btn">Hapus</button>' +
                        '</td>' +
                        '</tr>';

                    itemList.append(row);
                });

                // Clear input fields after adding items
                $('#jumlah').val('');
            });

            // Handle delete button click
            $(document).on('click', '.delete-btn', function() {
                $(this).closest('tr').remove();
            });

            // Handle pembayaran button click
            $('.btn-pembayaran').on('click', function() {
                var modal = $('#pembayaranReservasiModal');
                var aksesorisList = $(this).data('list-aksesoris');
                console.log(aksesorisList);
                var hargaLayanan = $(this).data('layanan-harga');
                var totalHarga = hargaLayanan;
                aksesorisList.forEach(function(aksesoris) {
                    totalHarga += aksesoris.jumlah * aksesoris.aksesoris.harga;
                });
                modal.find('#reservasi_id').val($(this).data('reservasi-id'));
                modal.find('#total').val(formatRupiah(totalHarga));
                modal.find('input[name="total"]').val(totalHarga);
            });

            $('#metode_pembayaran').on('change', function() {
                if ($(this).val() == 'cash') {
                    $('#buktiPembayaranWrapper').hide();
                } else {
                    $('#buktiPembayaranWrapper').show();
                }
            });

            // Trigger change event on page load to set the initial state
            $('#metode_pembayaran').trigger('change');
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }

        function calculateTotal(baseTotal) {
            var total = 0;
            $('#item-list tr').each(function() {
                var harga = $(this).find('td:nth-child(2)').text().replace(/[^0-9]/g, '');
                var jumlah = $(this).find('td:nth-child(3)').text();
                total += parseInt(harga) * parseInt(jumlah);
            });
            return formatRupiah(total + parseInt(baseTotal.replace(/[^0-9]/g, '')));
        }
    </script>
@endsection
