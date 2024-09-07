<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Barang Keluar {{ $customers->find($barangKeluar->customer_id)->name }}</title>
    <style>
        .container {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 20px;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 140px; /* Small size for the logo */
            height: auto;
        }

        .header-info {
            text-align: right;
            font-size: 14px;
        }

        .header-info strong {
            display: block;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 20px 0;
            border-radius: 10px;
            overflow: hidden;
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
            background-color: #4472C4;
            color: white;
        }

        .description {
            font-size: 16px;
        }

        .wire-transfer {
            margin-top: 20px;
            border: 1px solid black;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            display: inline-block;
            max-width: 100%;
        }

        .wire-transfer .header {
            text-decoration: underline;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .wire-transfer .row {
            display: flex;
            margin-bottom: 10px;
            align-items: center;
        }

        .wire-transfer .label {
            font-weight: bold;
            flex: 0 0 150px;
            margin-right: 10px;
        }

        .wire-transfer .colon {
            margin-right: 10px;
        }

        .wire-transfer .value {
            flex: 1;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo">
            <div class="header-info">
                <strong>Kantor Pusat:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->address }}<br>
                <strong>Kantor Cabang:</strong> Jl. Ancol Barat 6 No.3 Pademangan Jakarta Utara<br>
                <strong>Telp:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->phone_number }}<br>
                <strong>Email:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->email }}<br>
            </div>
        </div>

        <div>
            <h2>Invoice</h2>
            <table>
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $barangKeluar->nomer_invoice }}</td>
                        <td>{{ $barangKeluar->tanggal_keluar }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <h1>BILL TO</h1>
        <div class="header-info" style="font-weight: bold; font-size: 16px">
            {{ $customers->find($barangKeluar->customer_id)->name ?? 'N/A' }}<br>
            {{ $customers->find($barangKeluar->customer_id)->address ?? 'N/A' }}<br>
            {{ $customers->find($barangKeluar->customer_id)->no_hp ?? 'N/A' }}<br>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Job Order</th>
                    <th>No Kontainer</th>
                    <th>QTY</th>
                    <th colspan="3" class="description">Description</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0; // Inisialisasi variabel untuk menyimpan total harga
                @endphp
                @foreach ($barangKeluar->items as $item)
                @php
                    $total += $item->total_harga; // Menambahkan harga item ke total
                @endphp
                <tr>
                    <td>{{ $item->no_ref }}</td>
                    <td><strong>{{ $barangMasuks[$item->barang_masuk_id]->nomer_container }}</strong></td>
                    <td>{{ $item->qty }} {{ $item->unit }}</td>
                    <td colspan="3" class="description" style="text-align: center; font-size: 12px">
                        Kontainer <br> <strong>{{ $barangMasuks[$item->barang_masuk_id]->jenis_mobil ?? 'N/A' }}</strong> <br>
                        Masa Penimpun: <br> <strong>{{ $barangMasuks[$item->barang_masuk_id]->tanggal_masuk ?? 'N/A' }} - {{ $barangKeluar->tanggal_keluar }}</strong>
                    </td>
                    <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" style="text-align: right; font-weight: bold;">Total:</td>
                    <td>Rp. {{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>


        <div class="wire-transfer">
            <strong class="header">W I R E T R A N S F E R</strong>

            <div class="row" style="margin-top: 10px;">
                <span class="label">Bank Transfer</span>
                <span class="colon" margin-left="10px">:</span>
                <span class="value">{{ $bankTransfers->find($barangKeluar->bank_transfer_id)->bank_name ?? 'N/A' }}</span>
            </div>

            <div class="row">
                <span class="label">A/C Number</span>
                <span class="colon" style="margin-left: 15px;">:</span>
                <span class="value">{{ $bankTransfers->find($barangKeluar->bank_transfer_id)->account_number ?? 'N/A' }}</span>
            </div>

            <div class="row">
                <span class="label">A/C Name</span>
                <span class="colon" style="margin-left: 30px;">:</span>
                <span class="value">{{ $bankTransfers->find($barangKeluar->bank_transfer_id)->account_name ?? 'N/A' }}</span>
            </div>
        </div>
    </div>
</body>

</html>
