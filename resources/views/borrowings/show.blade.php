@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 800px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Detail Peminjaman
                    #{{ $borrowing->id }}</h1>
                <a href="{{ route('borrowings.index') }}"
                    style="color: var(--color-text-muted); text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="ph ph-arrow-left" style="margin-right: 0.5rem;"></i>
                    Kembali
                </a>
            </div>

            @if(session('error'))
                <div
                    style="background-color: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div
                    style="background-color: #f0fdf4; border-left: 4px solid #4ade80; color: #166534; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase;">
                        Informasi Peminjam</h3>
                    <p style="font-weight: 500;">{{ $borrowing->user->name }}</p>
                    <p style="color: var(--color-text-muted);">{{ $borrowing->user->email }}</p>
                </div>
                <div>
                    <h3 style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase;">Barang
                        Dipinjam</h3>
                    <p style="font-weight: 500; color: var(--color-primary);">{{ $borrowing->item->name }}</p>
                    <p>Jumlah: {{ $borrowing->quantity }} Unit</p>
                    <p style="font-size: 0.875rem; color: var(--color-text-muted);">Kode: {{ $borrowing->item->code }}
                    </p>
                </div>
            </div>

            <div
                style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted);">Tanggal
                            Pinjam</span>
                        <span style="font-weight: 600;">{{ $borrowing->borrow_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted);">Jatuh
                            Tempo</span>
                        <span style="font-weight: 600;">{{ $borrowing->due_date->format('d M Y') }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted);">Tanggal
                            Kembali</span>
                        <span
                            style="font-weight: 600;">{{ $borrowing->return_date ? $borrowing->return_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div>
                        <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted);">Status</span>
                        <span
                            style="font-weight: 700; text-transform: uppercase; color: var(--color-primary);">{{ $borrowing->status }}</span>
                    </div>
                </div>
                @if($borrowing->notes)
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                        <span style="display: block; font-size: 0.75rem; color: var(--color-text-muted);">Catatan:</span>
                        <p>{{ $borrowing->notes }}</p>
                    </div>
                @endif
            </div>

            @if(Auth::user()->isAdmin())
                <div class="flex justify-end gap-4">
                    @if($borrowing->status === 'pending')
                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn" style="background: #fee2e2; color: #991b1b;">
                                <i class="ph ph-x-circle" style="margin-right: 0.5rem;"></i> Tolak Pengajuan
                            </button>
                        </form>

                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-check-circle" style="margin-right: 0.5rem;"></i> Setujui Peminjaman
                            </button>
                        </form>
                    @endif

                    @if($borrowing->status === 'approved')
                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="returned">
                            <button type="submit" class="btn" style="background: #dbeafe; color: #1e40af;">
                                <i class="ph ph-arrow-u-up-left" style="margin-right: 0.5rem;"></i> Konfirmasi Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            @else
                @if($borrowing->status === 'pending')
                    <div class="flex justify-end">
                        <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST"
                            onsubmit="return confirm('Batalkan pengajuan ini?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn" style="background: #fee2e2; color: #991b1b;">
                                <i class="ph ph-trash" style="margin-right: 0.5rem;"></i> Batalkan Pengajuan
                            </button>
                        </form>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection