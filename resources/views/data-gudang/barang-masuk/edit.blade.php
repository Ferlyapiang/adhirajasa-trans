<!-- resources/views/data-gudang/barang-masuk/edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Barang Masuk</title>

    <!-- Font Awesome Icons -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        @include('admin.header')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('admin.sidebar')
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid pl-4">
                    <div class="row mb-1">
                        <div class="col-sm-12" style="border: 1px solid #D0D4DB; border-radius: 10px; background-color: white; padding: 10px;">
                            <h1 class="m-0" style="font-weight: bold; font-size: 16px; padding-left: 10px;">
                                <span style="font-weight: 370;">Data Gudang |</span> 
                                <span>Barang Masuk</span>
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <h1>Edit Barang Masuk</h1>
                <form action="{{ route('master-data.barang-masuk.update', $barangMasuk->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="tanggal_masuk">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ $barangMasuk->tanggal_masuk }}" required>
                    </div>

                    <div class="form-group">
                        <label for="no_ref">No. Referensi</label>
                        <input type="text" name="no_ref" id="no_ref" class="form-control" value="{{ $barangMasuk->no_ref }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <select name="nama_barang" id="nama_barang" class="form-control" required>
                            @foreach($barangs as $barang)
                                <option value="{{ $barang->id }}" {{ $barangMasuk->nama_barang == $barang->id ? 'selected' : '' }}>{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="nama_pemilik">Nama Pemilik</label>
                        <select name="nama_pemilik" id="nama_pemilik" class="form-control" required>
                            @foreach($pemilik as $owner)
                                <option value="{{ $owner->id }}" {{ $barangMasuk->nama_pemilik == $owner->id ? 'selected' : '' }}>{{ $owner->nama_pemilik }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="gudang">Gudang</label>
                        <select name="gudang" id="gudang" class="form-control" required>
                            @foreach($gudangs as $gudang)
                                <option value="{{ $gudang->id }}" {{ $barangMasuk->gudang == $gudang->id ? 'selected' : '' }}>{{ $gudang->nama_gudang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_mobil">Jenis Mobil</label>
                        <input type="text" name="jenis_mobil" id="jenis_mobil" class="form-control" value="{{ $barangMasuk->jenis_mobil }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nomer_polisi">Nomer Polisi</label>
                        <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control" value="{{ $barangMasuk->nomer_polisi }}" required>
                    </div>

                    <div class="form-group">
                        <label for="nomer_container">Nomer Container</label>
                        <input type="text" name="nomer_container" id="nomer_container" class="form-control" value="{{ $barangMasuk->nomer_container }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('master-data.barang-masuk.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('admin.footer')
        <!-- /.footer -->

    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
