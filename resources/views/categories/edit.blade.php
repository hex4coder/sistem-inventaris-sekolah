@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Edit Kategori:
                    {{ $category->name }}</h1>
                <a href="{{ route('categories.index') }}"
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

            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Nama Kategori</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $category->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi</label>
                    <textarea name="description" id="description" class="form-input"
                        rows="3">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="flex justify-between items-center"
                    style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                    <button type="button"
                        onclick="if(confirm('Yakin ingin menghapus kategori ini?')) document.getElementById('delete-form').submit();"
                        class="btn" style="background: #fee2e2; color: #991b1b;">
                        <i class="ph ph-trash" style="margin-right: 0.5rem;"></i>
                        Hapus
                    </button>

                    <div class="flex gap-4">
                        <a href="{{ route('categories.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                            <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-check" style="margin-right: 0.5rem;"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('categories.destroy', $category) }}" method="POST"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection