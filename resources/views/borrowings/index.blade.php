@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        @if(session('success'))
            <div
                style="background-color: #f0fdf4; border-left: 4px solid #4ade80; color: #166534; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Riwayat Peminjaman</h2>
                    <p style="color: var(--color-text-muted);">
                        @if(Auth::user()->isAdmin())
                            Kelola permintaan peminjaman
                        @else
                            Daftar peminjaman saya
                        @endif
                    </p>
                </div>

                <div class="flex gap-2">
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('borrowings.export.pdf') }}" class="btn" style="background: #fee2e2; color: #991b1b;">
                            <i class="ph ph-file-pdf" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                            PDF
                        </a>
                        <a href="{{ route('borrowings.export.csv') }}" class="btn" style="background: #dcfce7; color: #166534;">
                            <i class="ph ph-file-csv" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                            CSV
                        </a>
                    @endif
                    <a href="{{ route('borrowings.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus-circle" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                        Ajukan Peminjaman
                    </a>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Peminjam</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Barang</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Tgl Pinjam</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Jatuh Tempo
                            </th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Status</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $borrowing)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 1rem; font-weight: 500;">{{ $borrowing->user->name }}</td>
                                <td style="padding: 1rem;">{{ $borrowing->item->name }} ({{ $borrowing->quantity }})</td>
                                <td style="padding: 1rem;">{{ $borrowing->borrow_date->format('d M Y') }}</td>
                                <td style="padding: 1rem;">{{ $borrowing->due_date->format('d M Y') }}</td>
                                <td style="padding: 1rem;">
                                    @php
                                        $badgeColor = match ($borrowing->status) {
                                            'approved' => '#22c55e',
                                            'returned' => '#3b82f6',
                                            'rejected' => '#ef4444',
                                            'pending' => '#eab308',
                                        };
                                        $badgeBg = match ($borrowing->status) {
                                            'approved' => '#dcfce7',
                                            'returned' => '#dbeafe',
                                            'rejected' => '#fee2e2',
                                            'pending' => '#fef9c3',
                                        };
                                    @endphp
                                    <span
                                        style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">{{ $borrowing->status }}</span>
                                </td>
                                <td style="padding: 1rem;">
                                    <a href="{{ route('borrowings.show', $borrowing) }}"
                                        style="color: var(--color-primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                        <i class="ph ph-eye" style="margin-right: 0.25rem;"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 2rem; text-align: center; color: var(--color-text-muted);">
                                    Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $borrowings->links() }}
            </div>
        </div>
    </div>
@endsection