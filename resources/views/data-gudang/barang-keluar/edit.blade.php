<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang Keluar</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('ats/ATSLogo.png') }}" />
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

    <style>
        .form-group {
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn-danger {
            color: #fff;
            background-color: #dc3545;
            border-color: #dc3545;
        }
    </style>
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
                            <h1 class="m-0">Edit Barang Keluar</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Form Barang Keluar</h3>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('data-gudang.barang-keluar.update', $barangKeluar->id) }}"
                                        method="POST" id="barangKeluarForm">
                                        @csrf
                                        @method('PUT')

                                        <div class="form-group" hidden>
                                            <label for="nomer_invoice">Nomor Invoice</label>
                                            <input type="text" name="nomer_invoice" id="nomer_invoice"
                                                class="form-control @error('nomer_invoice') is-invalid @enderror"
                                                value="{{ old('nomer_invoice', $barangKeluar->nomer_invoice) }}" readonly>
                                            @error('nomer_invoice')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="nomer_surat_jalan">Nomor Surat Jalan</label>
                                            <input type="text" name="nomer_surat_jalan" id="nomer_invoice"
                                                class="form-control @error('nomer_surat_jalan') is-invalid @enderror" placeholder="Nomor Surat Jalan"
                                                value="{{ old('nomer_surat_jalan', $barangKeluar->nomer_surat_jalan) }}">
                                            @error('nomer_surat_jalan')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" id="tanggal_keluar"
                                                class="form-control @error('tanggal_keluar') is-invalid @enderror"
                                                value="{{ old('tanggal_keluar', $barangKeluar->tanggal_keluar) }}"
                                                required>
                                            @error('tanggal_keluar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="gudang_id">Gudang</label>
                                            <select name="gudang_id" id="gudang_id"
                                                class="form-control @error('gudang_id') is-invalid @enderror"
                                                style="pointer-events: none; background-color: #e9ecef;" required>
                                                <option value="">Select Gudang</option>
                                                @foreach ($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}"
                                                    {{ old('gudang_id', $barangKeluar->gudang_id) == $warehouse->id ? 'selected' : '' }}>
                                                    {{ $warehouse->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('gudang_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="customer_id">Pemilik Barang</label>
                                            <select name="customer_id" id="customer_id"
                                                class="form-control @error('customer_id') is-invalid @enderror"
                                                style="pointer-events: none; background-color: #e9ecef;" required>
                                                <option value="">Select Pemilik Barang</option>
                                                @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    {{ old('customer_id', $barangKeluar->customer_id) == $customer->id ? 'selected' : '' }}>
                                                    {{ $customer->name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('customer_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>


                                        <div class="form-group">
                                            <label for="shipping_option">Pilih Opsi Pengiriman</label>
                                            <select name="shipping_option" id="shipping_option" class="form-control" onchange="toggleFieldsKirim()">
                                                <option value="">Pilih Opsi Pengiriman</option>
                                                <option value="kirim">Kirim</option>
                                                <option value="takeout">Pick Up</option>
                                            </select>
                                        </div>
                                        
                                        <!-- Tipe Mobil -->
                                        <div class="form-group" id="mobilField" style="display: none;">
                                            <label for="type_mobil_id">Tipe Mobil</label>
                                            <select name="type_mobil_id" id="type_mobil_id" class="form-control @error('type_mobil_id') is-invalid @enderror">
                                                <option value="">Pilih Tipe Mobil</option>
                                                @foreach ($typeMobilOptions as $typeMobil)
                                                <option value="{{ $typeMobil->id }}" {{ (old('type_mobil_id', $barangKeluar->type_mobil_id) == $typeMobil->id) ? 'selected' : '' }}>
                                                    {{ $typeMobil->type }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('type_mobil_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <!-- Harga Kirim Barang -->
                                        <div class="form-group" id="hargaKirimField" style="display: none;">
                                            <label for="harga_kirim_barang">Harga Kirim Barang</label>
                                            <input type="text" name="harga_kirim_barang" id="harga_kirim_barang"
                                                class="form-control @error('harga_kirim_barang') is-invalid @enderror"
                                                value="{{ old('harga_kirim_barang', $barangKeluar->harga_kirim_barang) }}">
                                            @error('harga_kirim_barang')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        
                                        <!-- Alamat Kirim -->
                                        <div class="form-group" id="alamatField" style="display: none;">
                                            <label for="address">Alamat Kirim</label>
                                            <textarea name="address" id="address" placeholder="Alamat"
                                                    class="form-control @error('address') is-invalid @enderror"
                                                    rows="4">{{ old('address', $barangKeluar->address) }}</textarea>

                                            @error('address')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="id_selection" class="form-label">Choose Identification Type:</label>
                                            <select id="id_selection" class="form-control" onchange="toggleFields()" required>
                                                <option value="">-- Select --</option>
                                                <option value="nomer_polisi" {{ $barangKeluar->nomer_polisi ? 'selected' : '' }}>Nomer Polisi</option>
                                                <option value="nomer_container" {{ $barangKeluar->nomer_container ? 'selected' : '' }}>Nomer Container</option>
                                            </select>
                                        </div>

                                        <div id="nomer_polisi_field" class="mb-3" style="display: none;">
                                            <label for="nomer_polisi">Nomer Polisi</label>
                                            <input type="text" name="nomer_polisi" id="nomer_polisi" class="form-control"
                                                value="{{ $barangKeluar->nomer_polisi }}">
                                        </div>

                                        <div id="nomer_container_field" class="mb-3" style="display: none;">
                                            <label for="nomer_container">Nomer Container</label>
                                            <input type="text" name="nomer_container" id="nomer_container" class="form-control"
                                                value="{{ $barangKeluar->nomer_container }}">
                                        </div>


                                        <div class="form-group">
                                            <label for="harga_lembur">Harga Lembur</label>
                                            <input type="text" id="display_harga_lembur" class="form-control"
                                                value="{{ number_format($barangKeluar->harga_lembur, 0, ',', '.') }}"
                                                oninput="formatRupiah(this, 'harga_lembur')">
                                            <input type="hidden" name="harga_lembur" id="harga_lembur"
                                                value="{{ $barangKeluar->harga_lembur }}">
                                        </div>




                                        <h2>Items</h2>
                                        <!-- Button trigger modal -->
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#itemModal">
                                            Add Item
                                        </button>

                                        <input type="hidden" name="items" id="items-input" value="[]">

                                        <div class="table-responsive">
                                            <table class="table" id="items-table">
                                                <thead>
                                                    <tr>
                                                        <th>Nomer Ref</th>
                                                        <th>Nama Barang</th>
                                                        <th>Quantity</th>
                                                        <th>Unit</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($barangKeluar->items as $item)
                                                    <tr>
                                                        <td>{{ $item->no_ref }}</td>
                                                        <td>{{ $item->barang->nama_barang }}</td>
                                                        <td>{{ $item->qty }}</td>
                                                        <td>{{ $item->unit }}</td>
                                                        </td>
                                                        {{-- <td>{{ $item->barang_id }}</td> --}}
                                                        <td style="display: none"
                                                            data-barang-id="{{ $item->barang_masuk_id }}">
                                                            {{ $item->barang_masuk_id }}
                                                        </td>
                                                        <td style="display: none"
                                                            data-barang-id="{{ $item->barang_id }}">
                                                            {{ $item->barang_id }}
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-warning edit-item">Edit</button>
                                                            <button type="button"
                                                                class="btn btn-danger remove-item">Remove</button>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- Items Table -->

                                        <br><br>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-success">Save</button>
                                            <a href="{{ route('data-gudang.barang-keluar.index') }}"
                                                class="btn btn-secondary">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.content -->
            </div>
            <!-- /.content-wrapper -->

            <!-- Footer -->

            <!-- /.footer -->
        </div>
        @include('admin.footer')
        <!-- ./wrapper -->

        <script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>

        <!-- Modal -->
        <div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="modal_no_ref" class="form-label">No. Ref</label>
                            <input type="text" class="form-control" id="modal_no_ref"
                                placeholder="Auto-generated" readonly>
                        </div>
                        <div class="form-group">
                            <label for="modal_barang_id" class="form-label">Barang</label>
                            <select class="form-control" id="modal_barang_id">
                                <option value="" data-jenis="" data-barang-masuk-id="">-- Pilih Barang --
                                </option>
                                @foreach ($barangs as $barang)
                                @foreach ($groupedBarangMasukItems as $barangMasukId => $items)
                                @foreach ($items as $item)
                                @if ($barang->id === $item->barang_id)
                                <option value="{{ $barang->id }}"
                                    data-jenis="{{ $barang->jenis }}"
                                    data-barang-masuk-id="{{ $item->barang_masuk_id }}">
                                    {{ $barang->nama_barang }}
                                </option>
                                @endif
                                @endforeach
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="modal_qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="modal_qty">
                        </div>
                        <div class="form-group">
                            <label for="modal_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="modal_unit" readonly>
                            <!-- Make it readonly -->
                        </div>
                        <input type="hidden" id="modal_barang_masuk_id">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="addItemButton">Add Item</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Item -->
        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog"
            aria-labelledby="editItemModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_modal_no_ref" class="form-label">No. Ref</label>
                            <input type="text" class="form-control" id="edit_modal_no_ref" readonly>
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_barang_id" class="form-label">Barang</label>
                            <select class="form-control readonly-select" id="edit_modal_barang_id" disabled>
                                <option value="" data-jenis="" data-barang-masuk-id="">-- Pilih Barang --
                                </option>
                                @foreach ($barangs as $barang)
                                @foreach ($groupedBarangMasukItems as $barangMasukId => $items)
                                @foreach ($items as $item)
                                @if ($barang->id === $item->barang_id)
                                <option value="{{ $barang->id }}"
                                    data-jenis="{{ $barang->jenis }}"
                                    data-barang-masuk-id="{{ $item->barang_masuk_id }}">
                                    {{ $barang->nama_barang }}
                                </option>
                                @endif
                                @endforeach
                                @endforeach
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_modal_qty" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_modal_qty">
                        </div>
                        <div class="form-group">
                            <label for="edit_modal_unit" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="edit_modal_unit" readonly>
                        </div>
                        <input type="hidden" id="edit_modal_barang_masuk_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveEditItemButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>



        <script>
            $(document).ready(function() {
                const barangSelect = $('#modal_barang_id');
                const noRefInput = $('#modal_no_ref');
                const barangMasukData = <?= json_encode($barangMasuks) ?>;

                barangSelect.on('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const barangMasukId = $(selectedOption).data('barang-masuk-id');

                    if (barangMasukId && barangMasukData[barangMasukId]) {
                        noRefInput.val(barangMasukData[barangMasukId].joc_number);
                    } else {
                        noRefInput.val('');
                    }

                    const jenis = $(selectedOption).data('jenis');
                    $('#modal_unit').val(jenis ? jenis : '');
                    $('#modal_barang_masuk_id').val(barangMasukId ? barangMasukId : '');
                });

                function formatCurrency(amount) {
                    let number = parseFloat(amount);
                    if (isNaN(number)) return 'Rp. 0';
                    return `Rp. ${number.toLocaleString('id-ID', { minimumFractionDigits: 0 })}`;
                }

                function parseCurrency(value) {
                    if (typeof value !== 'string') return 0;

                    // Perform the currency parsing
                    return parseFloat(value.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
                }


                function toRawNumber(value) {
                    return parseFloat(value.replace(/[^0-9]/g, '')) || 0;
                }

                function toInteger(value) {
                    return parseInt(value, 10) || 0;
                }

                function updateItemsInput() {
                    let items = [];
                    $('#items-table tbody tr').each(function() {
                        let row = $(this);
                        let item = {
                            barang_id: toInteger(row.find('td:eq(5)').text()),
                            no_ref: row.find('td:eq(0)').text(),
                            qty: toInteger(row.find('td:eq(2)').text()),
                            unit: row.find('td:eq(3)').text(),
                            barang_masuk_id: toInteger(row.find('td:eq(4)').text())
                        };
                        items.push(item);
                    });
                    $('#items-input').val(JSON.stringify(items));
                }

                updateItemsInput();

                $('#addItemButton').on('click', function() {
                    let barangId = barangSelect.val();
                    let barangName = barangSelect.find('option:selected').text();
                    let noRef = $('#modal_no_ref').val();
                    let qty = toInteger($('#modal_qty').val());
                    let unit = $('#modal_unit').val();
                    let barangMasukId = $('#modal_barang_masuk_id').val();


                    let itemExists = false;
                    $('#items-table tbody tr').each(function() {
                        let jocNumber = $(this).find('td:eq(0)').text();
                        let rowBarangId = $(this).find('td:eq(5)').text();
                        if (toInteger(rowBarangId) === toInteger(barangId) && jocNumber === noRef) {
                            itemExists = true;
                            return false;
                        }
                    });

                    if (itemExists) {
                        alert('Barang tersebut sudah ada di tabel.');
                        return;
                    }

                    let row = `<tr>
            <td>${noRef}</td>
            <td data-barang-id="${barangId}">${barangName}</td>
            <td>${qty}</td>
            <td>${unit}</td>
            <td style="display: none">${barangMasukId}</td>
            <td style="display: none">${barangId}</td>
            <td>
                <button type="button" class="btn btn-warning edit-item">Edit</button>
                <button type="button" class="btn btn-danger remove-item">Remove</button>
            </td>
        </tr>`;

                    $('#items-table tbody').append(row);
                    updateItemsInput();

                    // Reset modal fields after adding item
                    barangSelect.val('');
                    noRefInput.val('');
                    $('#modal_qty').val('');
                    $('#modal_unit').val('');
                    $('#modal_barang_masuk_id').val('');

                    $('#itemModal').modal('hide');
                });

                // Remove item from table
                $('#items-table').on('click', '.remove-item', function() {
                    $(this).closest('tr').remove();
                    updateItemsInput();
                });

                // Format harga input in modal
                $('#modal_harga').on('input', function() {
                    let value = $(this).val();
                    let parsedValue = parseCurrency(value);
                    $(this).val(formatCurrency(parsedValue));
                });

                let currentEditingRow;

                // Edit item in table
                $('#items-table').on('click', '.edit-item', function() {
                    currentEditingRow = $(this).closest('tr');

                    let barangId = currentEditingRow.find('td:eq(5)').text().trim();
                    let barangName = currentEditingRow.find('td:eq(1)').text().trim();
                    let noRef = currentEditingRow.find('td:eq(0)').text().trim();
                    let qty = currentEditingRow.find('td:eq(2)').text().trim();
                    let unit = currentEditingRow.find('td:eq(3)').text().trim();
                    let barangMasukId = currentEditingRow.find('td:eq(4)').text().trim();

                    $('#edit_modal_barang_id').val(barangId).change();
                    $('#edit_modal_no_ref').val(noRef);
                    $('#edit_modal_qty').val(qty);
                    $('#edit_modal_unit').val(unit);
                    $('#edit_modal_barang_masuk_id').val(barangMasukId);

                    $('#editItemModal').modal('show');
                });

                $('#saveEditItemButton').on('click', function() {
                    let barangId = $('#edit_modal_barang_id').val();
                    let barangName = $('#edit_modal_barang_id option:selected').text();
                    let noRef = $('#edit_modal_no_ref').val();
                    let qty = parseInt($('#edit_modal_qty').val());
                    let unit = $('#edit_modal_unit').val();
                    let barangMasukId = $('#edit_modal_barang_masuk_id').val();

                   

                    currentEditingRow.find('td:eq(0)').text(noRef);
                    currentEditingRow.find('td:eq(1)').text(barangName);
                    currentEditingRow.find('td:eq(2)').text(qty);
                    currentEditingRow.find('td:eq(3)').text(unit);
                    currentEditingRow.find('td:eq(4)').text(barangMasukId);
                    currentEditingRow.find('td:eq(5)').text(barangId);

                    $('#editItemModal').modal('hide');
                    updateItemsInput();
                });

                $('#edit_modal_harga').on('input', function() {
                    let value = $(this).val();
                    let parsedValue = parseCurrency(value);
                    $(this).val(formatCurrency(parsedValue));
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                var nomerPolisi = "{{ $barangKeluar->nomer_polisi }}";
                var nomerContainer = "{{ $barangKeluar->nomer_container }}";
                var idSelection = document.getElementById('id_selection');

                if (nomerPolisi) {
                    idSelection.value = 'nomer_polisi';
                    document.getElementById('nomer_polisi_field').style.display = 'block';
                    document.getElementById('nomer_container_field').style.display = 'none'; // Sembunyikan field lainnya
                } else if (nomerContainer) {
                    idSelection.value = 'nomer_container';
                    document.getElementById('nomer_container_field').style.display = 'block';
                    document.getElementById('nomer_polisi_field').style.display = 'none'; // Sembunyikan field lainnya
                } else {
                    idSelection.value = '';
                }
            });

            function toggleFields() {
                var selection = document.getElementById('id_selection').value;

                document.getElementById('nomer_polisi_field').style.display = 'none';
                document.getElementById('nomer_container_field').style.display = 'none';

                document.getElementById('nomer_polisi').value = '';
                document.getElementById('nomer_container').value = '';

                if (selection === 'nomer_polisi') {
                    document.getElementById('nomer_polisi_field').style.display = 'block';
                } else if (selection === 'nomer_container') {
                    document.getElementById('nomer_container_field').style.display = 'block';
                }
            }

            function toggleLembur() {
                let select = document.getElementById('ada_lembur');
                let lemburSection = document.getElementById('lembur_section');
                if (select.value === 'ya') {
                    lemburSection.style.display = 'block'; // Tampilkan field harga lembur
                } else {
                    lemburSection.style.display = 'none'; // Sembunyikan field harga lembur
                }
            }

            // Function to format currency
            function formatRupiah(displayInput, hiddenInputId) {
                let angka = displayInput.value.replace(/[^,\d]/g, '');
                let formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(angka);
                displayInput.value = formatted.replace('IDR', '').trim();
                document.getElementById(hiddenInputId).value = angka;
            }

            function toggleFieldsKirim() {
    const shippingOption = document.getElementById('shipping_option').value;
    const mobilField = document.getElementById('mobilField');
    const hargaKirimField = document.getElementById('hargaKirimField');
    const alamatField = document.getElementById('alamatField');

    if (shippingOption === 'kirim') {
        mobilField.style.display = 'block';
        hargaKirimField.style.display = 'block';
        alamatField.style.display = 'block';
    } else if (shippingOption === 'takeout') {
        mobilField.style.display = 'block';
        hargaKirimField.style.display = 'none';
        alamatField.style.display = 'none';
    } else {
        mobilField.style.display = 'none';
        hargaKirimField.style.display = 'none';
        alamatField.style.display = 'none';
    }
}

        </script>


</body>

</html>