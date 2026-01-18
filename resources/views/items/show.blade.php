@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 800px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Detail Barang</h1>
                <a href="{{ route('items.index') }}"
                    style="color: var(--color-text-muted); text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="ph ph-arrow-left" style="margin-right: 0.5rem;"></i>
                    Kembali
                </a>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
                <div>
                    @if($item->image_path)
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}"
                            style="width: 100%; border-radius: 8px; box-shadow: var(--shadow-md);">
                    @else
                        <div
                            style="width: 100%; padding-bottom: 100%; background: #e2e8f0; border-radius: 8px; position: relative;">
                            <div
                                style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; color: #94a3b8;">
                                No Image
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-primary); margin-bottom: 0.5rem;">
                        {{ $item->name }}
                    </h2>
                    <p
                        style="font-family: monospace; color: var(--color-text-muted); margin-bottom: 1.5rem; font-size: 1.1rem;">
                        {{ $item->code }}
                    </p>

                    <div style="display: grid; grid-template-columns: auto 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <span style="font-weight: 600; color: var(--color-text-muted);">Kategori:</span>
                        <span>{{ $item->category->name }}</span>

                        <span style="font-weight: 600; color: var(--color-text-muted);">Lokasi:</span>
                        <span>{{ $item->location->name }}</span>

                        <span style="font-weight: 600; color: var(--color-text-muted);">Stok:</span>
                        <span>{{ $item->stock }} Unit</span>

                        <span style="font-weight: 600; color: var(--color-text-muted);">Kondisi:</span>
                        <span style="text-transform: capitalize;">{{ $item->condition }}</span>
                    </div>

                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 0.5rem;">Deskripsi</h3>
                        <p style="color: var(--color-text-muted); line-height: 1.6;">
                            {{ $item->description ?: 'Tidak ada deskripsi.' }}
                        </p>
                    </div>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('items.edit', $item) }}" class="btn btn-primary" style="text-decoration: none;">
                            <i class="ph ph-pencil-simple" style="margin-right: 0.5rem;"></i>
                            Edit Barang
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection