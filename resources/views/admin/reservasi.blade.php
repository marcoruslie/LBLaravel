@extends('layouts.layout')
@section('pagename')
    Reservasi
@endsection
@section('header')
    @include('admin.partial.adminNavbar')
@endsection
@section('content')
    <div class="w-75">
        <form method="POST" action="{{ route('search_reservasi') }}" class="d-flex mb-1 column-gap-2">
            @csrf
            <input name="search" class="w-75 class p-2 rounded" type="text" placeholder="Search...">
            <button type="submit" class="btn btn-secondary rounded w-25">Search</button>
        </form>
        <table class="table rounded border table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nama Pelanggan</th>
                    <th scope="col">Tanggal/Jam Reservasi</th>
                    <th scope="col">Aksesoris</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allReservasi as $key => $reservasi)
                    <tr>
                        <th scope="row">{{ $key + 1 }}</th>
                        <td>{{ $reservasi->pelanggan->nama }}</td>
                        <td>{{ $reservasi->tanggal_reservasi }} / {{ $reservasi->jam_reservasi }}</td>
                        <td>{{ $reservasi->jenis_aksesoris }}</td>
                        <td>{{ $reservasi->status_reservasi }}</td>
                        <td class="d-flex column-gap-2">
                            @if ($reservasi->status_reservasi == 'Menunggu Konfirmasi')
                                <form method="POST" action="{{ route('update_status') }}">
                                    @csrf
                                    <input type="hidden" name="reservasi_id" value="{{ $reservasi->id }}">
                                    <input type="hidden" name="status" value="Dikerjakan">
                                    <button type="submit" class="btn btn-primary">Terima</button>
                                </form>
                                <form method="POST" action="{{ route('update_status') }}">
                                    @csrf
                                    <input type="hidden" name="reservasi_id" value="{{ $reservasi->id }}">
                                    <input type="hidden" name="status" value="Ditolak">
                                    <button type="submit" class="btn btn-danger">Tolak</button>
                                </form>
                            @elseif ($reservasi->status_reservasi == 'Dikerjakan')
                                <form method="POST" action="{{ route('update_status') }}">
                                    @csrf
                                    <input type="hidden" name="reservasi_id" value="{{ $reservasi->id }}">
                                    <input type="hidden" name="status" value="Selesai">
                                    <button type="submit" class="btn btn-success">Selesai</button>
                                </form>
                            @endif
                            <!-- Button trigger modal -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop" data-id="{{ $reservasi->id }}"
                                data-nama="{{ $reservasi->pelanggan->nama }}"
                                data-tanggal="{{ $reservasi->tanggal_reservasi }}"
                                data-jam="{{ $reservasi->jam_reservasi }}"
                                data-status="{{ $reservasi->status_reservasi }}"
                                data-keterangan="{{ $reservasi->keterangan }}"
                                data-item="{{ $reservasi->aksesorisLayanan }}"
                                data-payment="{{ $reservasi->pembayaran }}">
                                Detail
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Reservation Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nama Pelanggan:</strong> <span id="modalNama"></span></p>
                        <p><strong>Tanggal Reservasi:</strong> <span id="modalTanggal"></span></p>
                        <p><strong>Jam Reservasi:</strong> <span id="modalJam"></span></p>
                        <p><strong>Status:</strong> <span id="modalStatus"></span></p>
                        <p><strong>Keterangan:</strong> <span id="modalKeterangan"></span></p>
                        <p><strong>Items:</strong></p>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Jumlah Yang Diambil</th>
                                </tr>
                            </thead>
                            <tbody id="modalItems"></tbody>
                        </table>
                        <p><strong>Pembayaran:</strong></p>
                        <p id="modalPembayaran"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
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
            $('#staticBackdrop').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                var nama = button.data('nama');
                var tanggal = button.data('tanggal');
                var jam = button.data('jam');
                var status = button.data('status');
                var keterangan = button.data('keterangan');
                var items = button.data('item');
                var payment = button.data('payment');

                var modal = $(this);
                modal.find('#modalNama').text(nama);
                modal.find('#modalTanggal').text(tanggal);
                modal.find('#modalJam').text(jam);
                modal.find('#modalStatus').text(status);
                modal.find('#modalKeterangan').text(keterangan);

                // Clear the existing list
                var modalItems = modal.find('#modalItems');
                modalItems.empty();

                // Add each item to the table
                $.each(items, function(index, item) {
                    console.log(item)
                    modalItems.append('<tr><td>' + item.aksesoris.nama + '</td><td>' + formatRupiah(
                            item.aksesoris.harga) + '</td><td>' + item.aksesoris.stok +
                        '</td><td>' + item.jumlah + '</td></tr>');
                });

                // Handle payment details
                var modalPembayaran = modal.find('#modalPembayaran');
                if (payment) {
                    var paymentDetails = `<p><strong>No. Invoice:</strong> ${payment.no_invoice}</p>
                        <p><strong>Total:</strong> ${formatRupiah(payment.total)}</p>
                        <p><strong>Status:</strong> ${payment.status}</p>
                        <p><strong>Metode Pembayaran:</strong> ${payment.metode_pembayaran}</p>
                        <p><strong>Bukti Pembayaran:</strong> ${payment.bukti_pembayaran ? '<a href="storage/' + payment.bukti_pembayaran + '" target="_blank">Lihat Bukti</a>' : 'Belum ada bukti pembayaran'}</p>`;
                    modalPembayaran.html(paymentDetails);
                } else {
                    modalPembayaran.html('<p>User belum membayar reservasi ini.</p>');
                }
            });
        });

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(number);
        }
    </script>
@endsection
