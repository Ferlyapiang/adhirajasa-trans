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
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #e0f7fa; /* Warna biru muda */
        }
        .header-info {
            margin-bottom: 20px;
        }
        .header-info strong {
            display: block;
            margin-bottom: 5px;
        }
        img {
            max-width: 150px;
            max-height: 100px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo">
        {{-- <img src="{{ asset('ats/ATSLogo.png') }}" alt="ATS Logo"> --}}

        <div class="header-info">
            <strong>Kantor Pusat:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->address }}<br>
            <strong>Kantor Cabang:</strong> Jl. Ancol Barat 6 No.3 Pademangan Jakarta Utara<br>
            <strong>Telp:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->phone_number }}<br>
            <strong>Email:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->email }}<br>
            <strong>Tanggal Keluar:</strong> {{ $barangKeluar->tanggal_keluar }}<br>
            <strong>Customer:</strong> {{ $customers->find($barangKeluar->customer_id)->name ?? 'N/A' }}<br>
            <strong>Nomor Container:</strong> {{ $barangKeluar->nomer_container }}<br>
            <strong>Nomor Polisi:</strong> {{ $barangKeluar->nomer_polisi }}<br>
            <strong>Bank Transfer:</strong> {{ $bankTransfers->find($barangKeluar->bank_transfer_id)->bank_name ?? 'N/A' }} - {{ $bankTransfers->find($barangKeluar->bank_transfer_id)->account_number ?? 'N/A' }}<br>
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

        <h2>Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Job Order</th>
                    <th>No Kontainer</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluar->items as $item)
                    <tr>
                        <td>{{ $item->no_ref }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->unit }}</td>
                        <td>Rp. {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>Rp. {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
