<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Invoice Barang Masuk</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        @include('admin.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <x-sidebar />
        <!-- /.sidebar -->

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Invoice Barang Masuk</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Invoice Barang Masuk</h3>
                                    <button id="updateStatusButton" class="btn btn-success float-right">Update Status</button>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="paymentTypeFilter">Tipe Pembayaran:</label>
                                            <select id="paymentTypeFilter" class="form-control">
                                                <option value="">Semua</option>
                                                <option value="Akhir Bulan">Akhir Bulan</option>
                                                <option value="Pertanggal Masuk">Pertanggal Masuk</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="ownerNameFilter">Nama Pemilik:</label>
                                            <select id="ownerNameFilter" class="form-control">
                                                <option value="">Semua</option>
                                                @foreach ($owners as $owner)
                                                <option value="{{ $owner }}">{{ $owner }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="barangMasukTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="selectAllCheckbox"></th> <!-- Checkbox for select all -->
                                                    <th>No</th>
                                                    <th>Tanggal Tagihan</th>
                                                    <th>Job Order</th>
                                                    <th>Nama Pemilik</th>
                                                    <th>Gudang</th>
                                                    <th>Tipe Pembayaran Customer</th>
                                                    <th>Harga Simpan Barang</th>
                                                    <th>Harga Lembur</th>
                                                    <th>Total Masuk</th>
                                                    <th>Total Keluar</th>
                                                    <th>Total Sisa</th>
                                                    <th>Total Harga Simpan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceMasuk as $index => $item)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="invoiceCheckbox" 
                                                               value="{{ json_encode(['invoice_id' => $item->invoice_id, 'tanggal_tagihan_masuk' => $item->tanggal_tagihan_masuk]) }}">
                                                    </td>
                                                    
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->tanggal_tagihan_masuk }}</td>
                                                    <td>
                                                        <a href="{{ route('data-gudang.barang-masuk.detail', $item->invoice_id) }}">
                                                            {{ $item->joc_number }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $item->nama_customer }}</td>
                                                    <td>{{ $item->nama_gudang }}</td>
                                                    <td>{{ $item->type_payment_customer }}</td>
                                                    <td>{{ number_format($item->harga_simpan_barang) }}</td>
                                                    <td>{{ number_format($item->harga_lembur) }}</td>
                                                    <td>{{ $item->total_qty_masuk }}</td>
                                                    <td>{{ $item->total_qty_keluar }}</td>
                                                    <td>{{ $item->total_sisa }}</td>
                                                    <td>{{ number_format($item->total_harga_simpan) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" style="text-align: right;">Total:</th>
                                                    <th id="totalHargaSimpan"></th>
                                                    <th id="totalHargaLembur"></th>
                                                    <th id="totalMasuk"></th>
                                                    <th id="totalKeluar"></th>
                                                    <th id="totalSisa"></th>
                                                    <th id="totalHargaSimpanKeseluruhan"></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Footer -->
        @include('admin.footer')
        <!-- /.footer -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/1.13.3/js/jquery.dataTables.min.js"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

    <!-- Page-specific script -->
    <script>
        $(document).ready(function() {
            var table = $('#barangMasukTable').DataTable();

            $('#selectAllCheckbox').prop('disabled', true);
            $('.invoiceCheckbox').prop('disabled', true);


            $('#paymentTypeFilter').on('change', function() {
                var filterValue = $(this).val();
                table.column(6).search(filterValue).draw(); 
                updateTotals(); 
            });

            $('#ownerNameFilter').on('change', function() {
            var selectedOwner = $(this).val(); 
            var filterValue = $(this).val();

            if (selectedOwner && selectedOwner !== "") {
                table.column(4).search(filterValue).draw();
                updateTotals(); // Hitung ulang total setelah filter diterapkan
                $('#selectAllCheckbox').prop('disabled', false);
                $('.invoiceCheckbox').prop('disabled', false);
            } else {
                table.column(4).search(filterValue).draw();
                updateTotals(); // Hitung ulang total meskipun filter direset
                $('#selectAllCheckbox').prop('checked', false).prop('disabled', true);
                $('.invoiceCheckbox').prop('checked', false).prop('disabled', true);
                // Reset totals if necessary
            }
        });

            $('#selectAllCheckbox').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.invoiceCheckbox').prop('checked', isChecked);
            });

            $('#updateStatusButton').on('click', function() {
    var selectedIds = [];
    $('.invoiceCheckbox:checked').each(function() {
        // Parse the value of each selected checkbox
        selectedIds.push(JSON.parse($(this).val()));
    });

    if (selectedIds.length > 0) {
        $.ajax({
            url: '{{ route("invoice.barang.masuk.update.status") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds.map(item => item.invoice_id), // Only send invoice_id
                tanggal_tagihan_masuk: selectedIds[0].tanggal_tagihan_masuk // You can send the first one, assuming all have the same value
            },
            success: function(response) {
                alert('Status updated successfully!');
                window.location.reload();
            },
            error: function(xhr) {
                alert('Failed to update status. Please try again.');
                console.error(xhr.responseJSON);
            }
        });
    } else {
        alert('Please select at least one invoice.');
    }
});


            // Format Rupiah function
            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }

            // Update totals function
            function updateTotals() {
                let totalHargaSimpan = 0;
                let totalHargaLembur = 0;
                let totalMasuk = 0;
                let totalKeluar = 0;
                let totalSisa = 0;
                let totalHargaSimpanKeseluruhan = 0;

                table.rows({ filter: 'applied' }).every(function() {
                    var data = this.data();
                    totalHargaSimpan += parseFloat(data[7].replace(/,/g, '')) || 0;
                    totalHargaLembur += parseFloat(data[8].replace(/,/g, '')) || 0;
                    totalMasuk += parseFloat(data[9]) || 0;
                    totalKeluar += parseFloat(data[10]) || 0;
                    totalSisa += parseFloat(data[11]) || 0;
                    totalHargaSimpanKeseluruhan += parseFloat(data[12].replace(/,/g, '')) || 0;
                });

                $('#totalHargaSimpan').text(formatRupiah(totalHargaSimpan));
                $('#totalHargaLembur').text(formatRupiah(totalHargaLembur));
                $('#totalMasuk').text(totalMasuk);
                $('#totalKeluar').text(totalKeluar);
                $('#totalSisa').text(totalSisa);
                $('#totalHargaSimpanKeseluruhan').text(formatRupiah(totalHargaSimpanKeseluruhan));
            }

            // Initial totals calculation
            updateTotals();
        });
    </script>
</body>

</html>
