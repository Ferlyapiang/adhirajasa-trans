<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Surat Jalan PDF</title>
    <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 0 10%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('ATSLogo.jpg') }}" alt="ATS Logo">
        <h1>Surat Jalan</h1>
        <p>{{ $barangKeluar->nomer_surat_jalan }}</p>
        <p><strong>Tanggal:</strong> {{ date('d-m-Y') }}</p>
        <p><strong>Pemilik:</strong> {{ $customers->firstWhere('id', $barangKeluar->customer_id)->name }}</p>
    </div>

    <div class="content">
        <h3>Kami Kirimkan barang-barang tersebut di bawah ini:</h3>
        <table>
            <thead>
                <tr>
                    <th>Nomer Ref</th>
                    <th>Nama Barang</th>
                    <th>Quantity</th>
                    <th>Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($barangKeluar->items as $item)
                    <tr>
                        <td>{{ $item->no_ref }}</td>
                        <td>{{ $item->barang->nama_barang }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->unit }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Tanda Terima: _______________________</p>
        <p>Hormat Kami, ATS Digital</p>
    </div>
</body>
</html>
