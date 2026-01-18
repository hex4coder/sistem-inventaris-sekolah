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
    <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px;">
        @if(isset($settings['school_logo']) && $settings['school_logo'])
            <img src="{{ public_path('storage/' . $settings['school_logo']) }}" style="height: 60px; margin-bottom: 5px;">
        @endif
        <h2 style="margin: 0; text-transform: uppercase;">{{ $settings['school_name'] ?? 'Sistem Inventaris Sekolah' }}
        </h2>
        <p style="margin: 2px 0; font-size: 12px;">
            Tahun Ajaran: {{ $settings['academic_year'] ?? '-' }} | Semester: {{ $settings['semester'] ?? '-' }}
        </p>
        <h3 style="margin: 10px 0 0 0;">Laporan Peminjaman Barang</h3>
        <p style="margin: 2px 0; font-size: 10px;">Dicetak pada: {{ now()->format('d M Y H:i') }}</p>
    </div>

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

    <table style="width: 100%; margin-top: 40px; border: none;">
        <tr>
            <td style="border: none; width: 60%;"></td>
            <td style="border: none; width: 40%; text-align: center;">
                <p>Mengetahui,</p>
                <p>Wakabid Sarana dan Prasarana</p>
                <br><br><br>
                <p style="font-weight: bold; text-decoration: underline;">
                    {{ $settings['sarpras_name'] ?? '..........................' }}
                </p>
                <p>NIP. {{ $settings['sarpras_nip'] ?? '..........................' }}</p>
            </td>
        </tr>
    </table>

    <div style="page-break-before: always;">
        <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 20px;">Lampiran Foto Bukti</h3>

        @foreach($borrowings as $borrowing)
            @if($borrowing->approval_photo_path || $borrowing->return_photo_path)
                <div style="margin-bottom: 30px; border: 1px solid #ddd; padding: 10px;">
                    <p style="font-weight: bold; margin-bottom: 5px;">
                        Peminjaman #{{ $borrowing->id }} - {{ $borrowing->user->name }} ({{ $borrowing->item->name }})
                    </p>
                    <p style="font-size: 11px; color: #444; margin-bottom: 10px; font-style: italic;">
                        Catatan: {{ $borrowing->notes ?? '-' }}
                    </p>
                    <table style="width: 100%; border: none;">
                        <tr>
                            @if($borrowing->approval_photo_path)
                                <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 5px;">
                                    <p style="font-size: 10px; color: #000; font-weight: bold; margin-bottom: 2px;">Bukti Serah
                                        Terima</p>
                                    <p style="font-size: 10px; color: #666; margin-bottom: 5px;">Tgl:
                                        {{ $borrowing->borrow_date->format('d M Y') }}</p>
                                    <img src="{{ public_path('storage/' . $borrowing->approval_photo_path) }}"
                                        style="max-width: 90%; max-height: 200px; border: 1px solid #eee;">
                                </td>
                            @endif

                            @if($borrowing->return_photo_path)
                                <td style="width: 50%; text-align: center; vertical-align: top; border: none; padding: 5px;">
                                    <p style="font-size: 10px; color: #000; font-weight: bold; margin-bottom: 2px;">Bukti
                                        Pengembalian</p>
                                    <p style="font-size: 10px; color: #666; margin-bottom: 5px;">Tgl:
                                        {{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</p>
                                    <img src="{{ public_path('storage/' . $borrowing->return_photo_path) }}"
                                        style="max-width: 90%; max-height: 200px; border: 1px solid #eee;">
                                </td>
                            @endif
                        </tr>
                    </table>
                </div>
            @endif
        @endforeach
    </div>

    @php
        $rejectedBorrowings = $borrowings->where('status', 'rejected');
    @endphp

    @if($rejectedBorrowings->count() > 0)
        <div style="page-break-before: always;">
            <h3 style="text-align: center; text-transform: uppercase; margin-bottom: 20px;">Lampiran Peminjaman Ditolak</h3>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="width: 5%;">No</th>
                        <th style="width: 20%;">Peminjam</th>
                        <th style="width: 20%;">Barang</th>
                        <th style="width: 15%;">Tgl Pengajuan</th>
                        <th style="width: 40%;">Alasan Penolakan (Catatan)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rejectedBorrowings as $index => $borrowing)
                        <tr>
                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                            <td>{{ $borrowing->user->name }}</td>
                            <td>{{ $borrowing->item->name }}</td>
                            <td style="text-align: center;">{{ $borrowing->borrow_date->format('d/m/Y') }}</td>
                            <td style="color: #c00; font-style: italic;">{{ $borrowing->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- End of PDF Content -->
</body>

</html>
