@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Tambah Pengguna Baru</h1>
                <a href="{{ route('users.index') }}"
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

            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" required autofocus
                        placeholder="John Doe">
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}" required
                        placeholder="nama@sekolah.id">
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Role Akses</label>
                    <select name="role" id="role" class="form-input" required>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>Staff (Peminjam)</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator (Pengelola)
                        </option>
                    </select>
                </div>

                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                    <p style="margin-bottom: 1rem; font-weight: 600; color: var(--color-text);">Password Akun</p>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-input" required minlength="8"
                            placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input"
                            required minlength="8" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex justify-end gap-4" style="margin-top: 1rem;">
                    <a href="{{ route('users.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                        <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check" style="margin-right: 0.5rem;"></i> Tambah Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection