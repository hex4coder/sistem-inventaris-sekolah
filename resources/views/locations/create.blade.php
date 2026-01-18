@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Tambah Lokasi</h1>
                <a href="{{ route('locations.index') }}"
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

            <form method="POST" action="{{ route('locations.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lokasi</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required
                        autofocus>
                </div>

                <div class="form-group">
                    <label for="description" class="form-label">Deskripsi (Opsional)</label>
                    <textarea name="description" id="description" class="form-input"
                        rows="3">{{ old('description') }}</textarea>
                </div>

                <div class="flex justify-end gap-4" style="margin-top: 1rem;">
                    <a href="{{ route('locations.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                        <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check" style="margin-right: 0.5rem;"></i> Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection