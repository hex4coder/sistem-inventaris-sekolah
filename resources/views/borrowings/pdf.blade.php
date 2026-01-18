<!DOCTYPE html>
<html>

<head>
    <title>Laporan Peminjaman</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 0;
        }
    </style>
</head>

<body>
    <h1>Laporan Peminjaman Barang</h1>
    <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Peminjam</th>
                <th style="width: 20%;">Barang</th>
                <th style="width: 10%;">Jumlah</th>
                <th style="width: 15%;">Tgl Pinjam</th>
                <th style="width: 15%;">Tgl Kembali</th>
                <th style="width: 15%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($borrowings as $index => $borrowing)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $borrowing->user->name }}</td>
                    <td>{{ $borrowing->item->name }}</td>
                    <td>{{ $borrowing->quantity }}</td>
                    <td>{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                    <td>
                        {{ $borrowing->return_date ? $borrowing->return_date->format('d/m/Y') : '-' }}
                    </td>
                    <td>
                        {{ ucfirst($borrowing->status) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>