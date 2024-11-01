<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adhirajasa Trans Sejahtera - Data Invoice</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- AdminLTE Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.3/css/jquery.dataTables.min.css">

    <!-- xlxs library for exporting Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.6/xlsx.full.min.js"></script>

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
                            <h1 class="m-0">Data Invoice</h1>
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
                                    <button id="exportButton" class="btn btn-success mb-3">Download to Excel</button>
                                    <button id="updateStatusButton" class="btn btn-success float-right">Update
                                        Status</button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
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
                                                <th><input type="checkbox" id="selectAllCheckbox"></th>
                                                <!-- Checkbox for select all -->
                                                <th>No</th>
                                                <th>Tanggal Penagihan</th>
                                                <th>Nomer Referensi</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Nama Pemilik</th>
                                                <th>Gudang</th>
                                                <th>Total QTY Masuk</th>
                                                <th>Total QTY Keluar</th>
                                                <th>Total QTY Sisa</th>
                                                <th>Lemburan</th>                                                
                                                <th>Harga Kirim Barang</th>
                                                <th>Total Harga Simpan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceMaster as $index => $item)
                                                <tr>
                                                    <td><input type="checkbox" class="invoiceCheckbox"
                                                            value="{{ $item->id }}"></td>
                                                    <!-- Individual checkbox -->
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $item->tanggal_tagihan_masuk ?: $item->tanggal_tagihan_keluar ?: '' }}
                                                    <td>
                                                        <a
                                                            href="{{ $item->joc_number ? route('data-gudang.barang-masuk.detail', $item->barang_masuks_id) : route('data-gudang.barang-keluar.showSuratJalan', $item->barang_keluars_id) }}">
                                                            {{ $item->joc_number ? $item->joc_number : $item->nomer_surat_jalan }}
                                                        </a>
                                                    </td>

                                                    <td>{{ $item->tanggal_masuk_barang }}</td>
                                                    <td>{{ $item->tanggal_keluar }}</td>
                                                    <td>
                                                        {{ $item->customer_masuk_name ? $item->customer_masuk_name : $item->customer_keluar_name }}
                                                    </td>
                                                    <td>{{ $item->warehouse_masuk_name ? $item->warehouse_masuk_name : $item->warehouse_keluar_name }}
                                                    </td>
                                                    </td>
                                                    </td>
                                                    <td>{{ $item->total_qty_masuk }}</td>
                                                    <td>{{ $item->total_qty_keluar }}</td>
                                                    <td>{{ $item->total_sisa }}</td>
                                                    <td>
                                                        @if (!is_null($item->harga_lembur_masuk) && $item->harga_lembur_masuk != 0)
                                                            {{ number_format($item->harga_lembur_masuk, 0, ',', '.') }}
                                                        @elseif(!is_null($item->harga_lembur_keluar) && $item->harga_lembur_keluar != 0)
                                                            {{ number_format($item->harga_lembur_keluar, 0, ',', '.') }}
                                                        @else
                                                            {{ '' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ number_format($item->harga_kirim_barang, 0, ',', '.') }}
                                                    <td>{{ number_format($item->total_harga_simpan, 0, ',', '.') }}
                                                    </td>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th colspan="8" style="text-align: right;">Total:</th>                                                
                                                <th id="totalMasuk"></th>
                                                <th id="totalKeluar"></th>
                                                <th id="totalSisa"></th>
                                                <th id="totalHargaLembur"></th>                                                
                                                <th id="totalHargaKirimBarang"></th>
                                                <th id="totalHargaSimpan"></th>
                                            </tr>
                                            <tr class="no-export">
                                                <th colspan="8" style="text-align: right;">Dari Total Harga Lembur +
                                                    Total Harga Simpan Barang + Total Harga Kirim Barang</th>
                                                <th colspan="2" style="text-align: right;">Total Keseluruhan:</th>
                                                <th id="totalKeseluruhan" colspan="5"></th>
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
            // Initialize DataTable
            var table = $('#barangMasukTable').DataTable();

            $('#selectAllCheckbox').prop('disabled', true);
            $('.invoiceCheckbox').prop('disabled', true);

            $('#ownerNameFilter').on('change', function() {
                var selectedOwner = $(this).val();
                var filterValue = $(this).val();

                if (selectedOwner && selectedOwner !== "") {
                    table.column(6).search(filterValue).draw();
                    calculateTotals(); // Hitung ulang total setelah filter diterapkan
                    $('#selectAllCheckbox').prop('disabled', false);
                    $('.invoiceCheckbox').prop('disabled', false);
                } else {
                    table.column(6).search(filterValue).draw();
                    calculateTotals(); // Hitung ulang total meskipun filter direset
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
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $.ajax({
                        url: "{{ route('invoice.generate') }}",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
                        },
                        success: function(response) {
                            alert('Invoices generated successfully!');
                            window.location.reload();
                        },
                        error: function(xhr) {
                            alert('Failed to generate invoices. Please try again.');
                            console.error(xhr.responseJSON);
                        }
                    });
                } else {
                    alert('Please select at least one invoice.');
                }
            });

            // Function to calculate totals for visible rows only
            function calculateTotals() {
                let totalHargaSimpan = 0;
                let totalHargaLembur = 0;
                let totalMasuk = 0;
                let totalKeluar = 0;
                let totalSisa = 0;
                let totalHargaKirimBarang = 0;

                // Loop through only the visible rows
                table.rows({
                    search: 'applied'
                }).every(function() {
                    let data = this.data();
                    totalMasuk += +data[8] || 0; // Total QTY Masuk
                    totalKeluar += +data[9] || 0; // Total QTY Keluar
                    totalSisa += +data[10] || 0; // Total QTY Sisa
                    totalHargaLembur += +data[11].replace(/\./g, '').replace(',',
                    '.'); 
                    totalHargaKirimBarang += +data[12].replace(/\./g, '').replace(',',
                    '.'); // Total Harga Kirim Barang
                    totalHargaSimpan += +data[13].replace(/\./g, '').replace(',',
                    '.');
                });

                // Calculate total keseluruhan
                const totalKeseluruhan = totalHargaSimpan + totalHargaLembur + totalHargaKirimBarang;

                // Update the footer with totals
                $('#totalHargaSimpan').text(totalHargaSimpan.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalHargaLembur').text(totalHargaLembur.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalMasuk').text(totalMasuk.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalKeluar').text(totalKeluar.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalSisa').text(totalSisa.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalHargaKirimBarang').text(totalHargaKirimBarang.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
                $('#totalKeseluruhan').text(totalKeseluruhan.toLocaleString('id-ID', {
                    minimumFractionDigits: 0
                }));
            }
            
            calculateTotals();
        });
        document.getElementById('exportButton').addEventListener('click', function() {
            var table = document.getElementById('barangMasukTable');
            var clonedTable = table.cloneNode(true);

            var noExportRows = clonedTable.querySelectorAll('.no-export');

            var workbook = XLSX.utils.table_to_book(clonedTable, {
                sheet: "Data Barang Keluar"
            });
            XLSX.writeFile(workbook, 'DataInvoiceMaster.xlsx');
        });
    </script>


</body>

</html>
