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

            @if($borrowing->approval_photo_path || $borrowing->return_photo_path)
                <div style="margin-bottom: 2rem;">
                    <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 1rem; color: var(--color-text);">Bukti Foto
                    </h3>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                        @if($borrowing->approval_photo_path)
                            <div style="border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 8px;">
                                <p
                                    style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.5rem; text-align: center;">
                                    Bukti Serah Terima</p>
                                <img src="{{ asset('storage/' . $borrowing->approval_photo_path) }}" alt="Bukti Serah Terima"
                                    style="width: 100%; height: auto; border-radius: 4px; border: 1px solid #f1f5f9; cursor: pointer;"
                                    onclick="openImageModal(this.src)">
                            </div>
                        @endif

                        @if($borrowing->return_photo_path)
                            <div style="border: 1px solid #e2e8f0; padding: 0.5rem; border-radius: 8px;">
                                <p
                                    style="font-size: 0.75rem; color: var(--color-text-muted); margin-bottom: 0.5rem; text-align: center;">
                                    Bukti Pengembalian</p>
                                <img src="{{ asset('storage/' . $borrowing->return_photo_path) }}" alt="Bukti Pengembalian"
                                    style="width: 100%; height: auto; border-radius: 4px; border: 1px solid #f1f5f9; cursor: pointer;"
                                    onclick="openImageModal(this.src)">
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(Auth::user()->isAdmin())
                <div class="flex justify-end gap-4">
                    @if($borrowing->status === 'pending')
                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST" class="confirm-form"
                            data-confirm-message="Tolak pengajuan peminjaman ini?">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn" style="background: #fee2e2; color: #991b1b;">
                                <i class="ph ph-x-circle" style="margin-right: 0.5rem;"></i> Tolak Pengajuan
                            </button>
                        </form>

                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST" class="confirm-form"
                            data-confirm-message="Setujui peminjaman ini? Stok barang akan berkurang."
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="approved">

                            <div style="margin-bottom: 1rem; text-align: left;">
                                <label for="approval_photo"
                                    style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--color-text-muted);">Foto
                                    Bukti Serah Terima (Opsional)</label>
                                <input type="file" name="approval_photo" id="approval_photo" class="form-input" accept="image/*"
                                    style="padding: 0.5rem; font-size: 0.875rem;">
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-check-circle" style="margin-right: 0.5rem;"></i> Setujui Peminjaman
                            </button>
                        </form>
                    @endif

                    @if($borrowing->status === 'approved')
                        <form action="{{ route('borrowings.update', $borrowing) }}" method="POST" class="confirm-form"
                            data-confirm-message="Apakah barang sudah dikembalikan dan diperiksa kondisinya?"
                            enctype="multipart/form-data">
                            @csrf @method('PUT')
                            <input type="hidden" name="status" value="returned">

                            <div style="margin-bottom: 1rem; text-align: left;">
                                <label for="return_photo"
                                    style="display: block; font-size: 0.875rem; margin-bottom: 0.5rem; color: var(--color-text-muted);">Foto
                                    Bukti Pengembalian (Opsional)</label>
                                <input type="file" name="return_photo" id="return_photo" class="form-input" accept="image/*"
                                    style="padding: 0.5rem; font-size: 0.875rem;">
                            </div>

                            <button type="submit" class="btn" style="background: #dbeafe; color: #1e40af;">
                                <i class="ph ph-arrow-u-up-left" style="margin-right: 0.5rem;"></i> Konfirmasi Pengembalian
                            </button>
                        </form>
                    @endif
                </div>
            @else
                @if($borrowing->status === 'pending')
                    <div class="flex justify-end">
                        <form action="{{ route('borrowings.destroy', $borrowing) }}" method="POST" class="delete-form">
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

@push('scripts')
    <script>
        function openImageModal(src) {
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100%';
            modal.style.height = '100%';
            modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            modal.style.display = 'flex';
            modal.style.justifyContent = 'center';
            modal.style.alignItems = 'center';
            modal.style.zIndex = '1000';
            modal.style.cursor = 'pointer';
            modal.onclick = function () { document.body.removeChild(modal); };

            const img = document.createElement('img');
            img.src = src;
            img.style.maxWidth = '90%';
            img.style.maxHeight = '90%';
            img.style.borderRadius = '8px';
            img.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';

            modal.appendChild(img);
            document.body.appendChild(modal);
        }
    </script>
@endpush
