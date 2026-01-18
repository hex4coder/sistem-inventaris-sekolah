@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Ajukan Peminjaman</h1>
                <a href="{{ route('borrowings.index') }}"
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

            <form method="POST" action="{{ route('borrowings.store') }}">
                @csrf

                <div class="form-group">
                    <label for="item_id" class="form-label">Pilih Barang</label>
                    <select name="item_id" id="item_id" class="form-input" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->name }} (Stok: {{ $item->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity" class="form-label">Jumlah</label>
                    <input type="number" name="quantity" id="quantity" class="form-input" value="{{ old('quantity', 1) }}"
                        min="1" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label for="borrow_date" class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="borrow_date" id="borrow_date" class="form-input"
                            value="{{ old('borrow_date', date('Y-m-d')) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="due_date" class="form-label">Rencana Kembali</label>
                        <input type="date" name="due_date" id="due_date" class="form-input" value="{{ old('due_date') }}"
                            required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Catatan / Keperluan (Opsional)</label>
                    <textarea name="notes" id="notes" class="form-input" rows="3">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-4" style="margin-top: 1rem;">
                    <a href="{{ route('borrowings.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                        <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-paper-plane-right" style="margin-right: 0.5rem;"></i> Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection