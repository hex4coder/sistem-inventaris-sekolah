@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">

        <div class="card">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1.5rem;">Pengaturan
                Sekolah</h2>

            <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="school_name" class="form-label">Nama Sekolah</label>
                    <input type="text" name="school_name" id="school_name" class="form-input"
                        value="{{ $settings['school_name'] ?? '' }}" required>
                </div>

                <div class="form-group">
                    <label for="academic_year" class="form-label">Tahun Ajaran Aktif</label>
                    <input type="text" name="academic_year" id="academic_year" class="form-input"
                        value="{{ $settings['academic_year'] ?? '' }}" placeholder="2023/2024" required>
                </div>

                <div class="form-group">
                    <label for="semester" class="form-label">Semester Aktif</label>
                    <select name="semester" id="semester" class="form-input" required>
                        <option value="Ganjil" {{ ($settings['semester'] ?? '') == 'Ganjil' ? 'selected' : '' }}>Ganjil
                        </option>
                        <option value="Genap" {{ ($settings['semester'] ?? '') == 'Genap' ? 'selected' : '' }}>Genap
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sarpras_name" class="form-label">Nama Wakasek Sarpras</label>
                    <input type="text" name="sarpras_name" id="sarpras_name" class="form-input"
                        value="{{ $settings['sarpras_name'] ?? '' }}" placeholder="Nama Lengkap dengan Gelar">
                </div>

                <div class="form-group">
                    <label for="sarpras_nip" class="form-label">NIP Wakasek Sarpras</label>
                    <input type="text" name="sarpras_nip" id="sarpras_nip" class="form-input"
                        value="{{ $settings['sarpras_nip'] ?? '' }}" placeholder="NIP">
                </div>

                <div class="form-group">
                    <label for="school_logo" class="form-label">Logo Sekolah</label>
                    @if(isset($settings['school_logo']) && $settings['school_logo'])
                        <div style="margin-bottom: 0.5rem;">
                            <img src="{{ asset('storage/' . $settings['school_logo']) }}" alt="School Logo"
                                style="height: 50px;">
                        </div>
                    @endif
                    <input type="file" name="school_logo" id="school_logo" class="form-input">
                    <p style="font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem;">Kosongkan jika
                        tidak ingin mengubah logo.</p>
                </div>

                <div class="flex justify-end gap-2" style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-floppy-disk" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection