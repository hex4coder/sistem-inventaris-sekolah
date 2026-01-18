@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Edit Pengguna: {{ $user->name }}
                </h1>
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

            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" id="name" class="form-input" value="{{ old('name', $user->name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-input" value="{{ old('email', $user->email) }}"
                        required>
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Role Akses</label>
                    <select name="role" id="role" class="form-input" required>
                        <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff (Peminjam)
                        </option>
                        <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru (Peminjam)
                        </option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrator
                            (Pengelola)</option>
                    </select>
                </div>

                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                    <p style="margin-bottom: 0.5rem; font-weight: 600; color: var(--color-text);">Ubah Password (Opsional)
                    </p>
                    <p style="margin-bottom: 1rem; font-size: 0.875rem; color: var(--color-text-muted);">Biarkan kosong jika
                        tidak ingin mengubah password.</p>

                    <div class="form-group">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" name="password" id="password" class="form-input" minlength="8"
                            placeholder="••••••••">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" class="form-input"
                            minlength="8" placeholder="••••••••">
                    </div>
                </div>

                <div class="flex justify-between items-center"
                    style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                    @if(auth()->id() !== $user->id)
                        <button type="button" onclick="document.getElementById('delete-form').requestSubmit();" class="btn"
                            style="background: #fee2e2; color: #991b1b;">
                            <i class="ph ph-trash" style="margin-right: 0.5rem;"></i>
                            Hapus Pengguna
                        </button>
                    @else
                        <button type="button" disabled class="btn"
                            style="background: #f1f5f9; color: #94a3b8; cursor: not-allowed;"
                            title="Anda tidak dapat menghapus akun sendiri">
                            <i class="ph ph-trash" style="margin-right: 0.5rem;"></i>
                            Hapus Pengguna
                        </button>
                    @endif

                    <div class="flex gap-4">
                        <a href="{{ route('users.index') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                            <i class="ph ph-x" style="margin-right: 0.5rem;"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-check" style="margin-right: 0.5rem;"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <form id="delete-form" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;"
                class="delete-form">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </div>
@endsection