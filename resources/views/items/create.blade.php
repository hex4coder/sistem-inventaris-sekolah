@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 800px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Tambah Barang Baru</h1>
                <a href="{{ route('items.index') }}"
                    style="color: var(--color-text-muted); text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="ph ph-arrow-left" style="margin-right: 0.5rem;"></i>
                    Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="alert-error">
                    <ul style="list-style: none;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('items.store') }}" enctype="multipart/form-data">
                @csrf

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Barang</label>
                        <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="code" class="form-label">Kode Barang</label>
                        <input type="text" name="code" id="code" class="form-input" value="{{ old('code') }}" required
                            placeholder="Contoh: LPT-001">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-input" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="location_id" class="form-label">Lokasi</label>
                        <select name="location_id" id="location_id" class="form-input" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-input"
                        rows="3">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label for="stock" class="form-label">Stok Awal</label>
                        <input type="number" name="stock" id="stock" class="form-input" value="{{ old('stock', 1) }}"
                            min="0" required>
                    </div>

                    <div class="form-group">
                        <label for="condition" class="form-label">Kondisi</label>
                        <select name="condition" id="condition" class="form-input" required>
                            <option value="good" {{ old('condition') == 'good' ? 'selected' : '' }}>Baik (Good)</option>
                            <option value="damaged" {{ old('condition') == 'damaged' ? 'selected' : '' }}>Rusak (Damaged)
                            </option>
                            <option value="maintenance" {{ old('condition') == 'maintenance' ? 'selected' : '' }}>
                                Perbaikan (Maintenance)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image" class="form-label">Gambar (Opsional)</label>
                        <input type="file" name="image" id="image" class="form-input" style="padding: 0.5rem;">
                    </div>
                </div>

                <div class="flex justify-end gap-4" style="margin-top: 1rem;">
                    <a href="{{ route('items.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                        <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check" style="margin-right: 0.5rem;"></i> Simpan Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection