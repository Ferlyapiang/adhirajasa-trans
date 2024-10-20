<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Invoice Barang Keluar</title>

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
                            <h1 class="m-0">Data Invoice Barang Keluar</h1>
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
                                    <h3 class="card-title">Daftar Invoice Barang Keluar</h3>
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
                                    </div>
                                    <div class="table-responsive">
                                        <table id="barangKeluarTable" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="selectAllCheckbox"></th> <!-- Checkbox for select all -->
                                                    <th>No</th>
                                                    <th>Tanggal Invoice Keluar</th>
                                                    <th>Nama Customer</th>
                                                    <th>Gudang</th>
                                                    <th>Tipe Pembayaran Customer</th>
                                                    <th>Nomor Surat Jalan</th>
                                                    <th>Harga Kirim Barang</th>
                                                    <th>Harga Lembur</th>
                                                    <th>Total Keluar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceKeluar as $index => $item)
                                                <tr>
                                                    <td><input type="checkbox" class="invoiceCheckbox" value="{{ $item->barang_keluar_id }}"></td> <!-- Individual checkbox -->
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->tanggal_tagihan_keluar }}</td>
                                                    <td>{{ $item->nama_customer }}</td>
                                                    <td>{{ $item->nama_gudang }}</td>
                                                    <td>{{ $item->type_payment_customer }}</td>
                                                    <td>{{ $item->nomer_surat_jalan }}</td>
                                                    <td>{{ number_format($item->harga_kirim_barang) }}</td>
                                                    <td>{{ number_format($item->harga_lembur) }}</td>
                                                    <td>{{ $item->total_qty }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="7" style="text-align: right;">Total:</th>
                                                    <th id="totalHargaKirim"></th>
                                                    <th id="totalHargaLembur"></th>
                                                    <th id="totalKeluar"></th>
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
            var table = $('#barangKeluarTable').DataTable();

            $('#paymentTypeFilter').on('change', function() {
                var filterValue = $(this).val();
                table.column(5).search(filterValue).draw();
                updateTotals();
            });

            $('#selectAllCheckbox').on('change', function() {
                var isChecked = $(this).is(':checked');
                $('.invoiceCheckbox').prop('checked', isChecked);
            });

            $('#updateStatusButton').on('click', function() {
                var selectedIds = [];
                $('.invoiceCheckbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $.ajax({
                        url: '{{ route("invoice.barang.keluar.update.status") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
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

            function updateTotals() {
                let totalHargaKirim = 0;
                let totalHargaLembur = 0;
                let totalKeluar = 0;

                table.rows({
                    filter: 'applied'
                }).every(function() {
                    var data = this.data();
                    totalHargaKirim += parseFloat(data[7].replace(/,/g, '')) || 0; // Harga Kirim Barang is at index 8
                    totalHargaLembur += parseFloat(data[8].replace(/,/g, '')) || 0; // Harga Lembur is at index 9
                    totalKeluar += parseFloat(data[9]) || 0; // Total Keluar is at index 10
                });

                $('#totalHargaKirim').text(formatRupiah(totalHargaKirim));
                $('#totalHargaLembur').text(formatRupiah(totalHargaLembur));
                $('#totalKeluar').text(totalKeluar);
            }


            function formatRupiah(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0
                }).format(value);
            }
            updateTotals();
        });
    </script>
</body>

</html>