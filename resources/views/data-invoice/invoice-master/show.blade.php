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
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="barangMasukTable" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Job Order</th>
                                                <th>No Kontainer</th>
                                                <th>QTY</th>
                                                <th>Description</th>
                                                <th>Unit Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceMaster as $index => $item)
                                            <tr>
                                                <td>{{ $item->joc_number ? $item->joc_number : $item->nomer_surat_jalan }}</td>
                                                <td>{{ $item->nomer_polisi_masuk ?: $item->nomer_polisi_keluar ?: $item->nomer_container_masuk ?: $item->nomer_container_keluar ?: ''}}</td>
                                                
                                                <td>{{ $item->total_qty_masuk ?: $item->total_qty_keluar_keluar ?: '' }}</td>
                                                <td> Kontainer <strong>{{ $item->type_mobil_masuk ?: $item->type_mobil_keluar ?: ''}}</strong> 
                                                    <br> 
                                                    Masa Penimbunan : <strong>{{  $item->tanggal_masuk_barang ?: $item->tanggal_keluar ?: ''}}</strong> - <strong>{{  $item->tanggal_tagihan_masuk ?: $item->tanggal_tagihan_keluar ?: ''}}</strong>  </td>
                                                <td>{{ number_format($item->total_harga_simpan_dan_lembur, 0, ',', '.') ?: number_format($item->total_harga_barang_keluar, 0, ',', '.') ?: '' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
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

</body>
<script>
    $(document).ready(function () {
    $('#barangMasukTable').DataTable({
        stateSave: true, // Simpan state DataTables
        "order": [[0, "desc"]] // Contoh untuk mengurutkan kolom pertama secara default
    });
});
window.onbeforeunload = function () {
    window.location.href = "{{ route('data-invoice.invoice-master.index') }}";
};

</script>
</html>