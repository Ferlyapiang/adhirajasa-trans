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
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Invoice for Barang Keluar {{ $customers->find($barangKeluar->customer_id)->name }}</h1>
        <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo" style="max-width: 150px; max-height: 100px;">
        {{-- <img src="{{ asset('ats/ATSLogo.png') }}" alt="ATS Logo" style="max-width: 150px; max-height: 100px;"> --}}

        <div>
            <strong>Nomer Invoice:</strong> {{ $barangKeluar->nomer_invoice }}<br>
            <strong>Tanggal Keluar:</strong> {{ $barangKeluar->tanggal_keluar }}<br>
            <strong>Gudang:</strong> {{ $warehouses->find($barangKeluar->gudang_id)->name ?? 'N/A' }}<br>
            <strong>Customer:</strong> {{ $customers->find($barangKeluar->customer_id)->name ?? 'N/A' }}<br>
            <strong>Nomor Container:</strong> {{ $barangKeluar->nomer_container }}<br>
            <strong>Nomor Polisi:</strong> {{ $barangKeluar->nomer_polisi }}<br>
            <strong>Bank Transfer:</strong> {{ $bankTransfers->find($barangKeluar->bank_transfer_id)->bank_name ?? 'N/A' }} - {{ $bankTransfers->find($barangKeluar->bank_transfer_id)->account_number ?? 'N/A' }}<br>
        </div>

        <h2>Items</h2>
        <table>
            <thead>
                <tr>
                    <th>Nomer Ref</th>
                    <th>Nama Barang</th>
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