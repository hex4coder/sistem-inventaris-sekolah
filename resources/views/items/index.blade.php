@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        @if(session('success'))
            <div style="background-color: #f0fdf4; border-left: 4px solid #4ade80; color: #166534; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <div>
                     <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Daftar Sarana & Prasarana</h2>
                     <p style="color: var(--color-text-muted);">Kelola data inventaris sekolah</p>
                </div>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('items.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                        Tambah Barang
                    </a>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Gambar</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Kode</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Nama Barang</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Kategori</th>
                             <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Lokasi</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Stok</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Kondisi</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                            <td style="padding: 1rem;">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <div style="width: 48px; height: 48px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 0.75rem;">No IMG</div>
                                @endif
                            </td>
                            <td style="padding: 1rem; font-family: monospace; color: var(--color-primary);">{{ $item->code }}</td>
                            <td style="padding: 1rem; font-weight: 500;">{{ $item->name }}</td>
                            <td style="padding: 1rem;">{{ $item->category->name }}</td>
                            <td style="padding: 1rem;">{{ $item->location->name }}</td>
                            <td style="padding: 1rem;">{{ $item->stock }}</td>
                            <td style="padding: 1rem;">
                                @php
                                    $badgeColor = match($item->condition) {
                                        'good' => '#22c55e',
                                        'damaged' => '#ef4444',
                                        'maintenance' => '#eab308',
                                    };
                                    $badgeBg = match($item->condition) {
                                        'good' => '#dcfce7',
                                        'damaged' => '#fee2e2',
                                        'maintenance' => '#fef9c3',
                                    };
                                @endphp
                                <span style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; text-transform: capitalize;">{{ $item->condition }}</span>
                            </td>
                            <td style="padding: 1rem;">
                                <div class="flex gap-4">
                                    <a href="{{ route('items.show', $item) }}" style="color: var(--color-primary); text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                        <i class="ph ph-eye" style="margin-right: 0.25rem;"></i> Lihat
                                    </a>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('items.edit', $item) }}" style="color: #ea580c; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                            <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Edit
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="padding: 2rem; text-align: center; color: var(--color-text-muted);">Belum ada data barang.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $items->links() }}
            </div>
        </div>
    </div>
@endsection
