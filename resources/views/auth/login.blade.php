@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="card" style="width: 100%; max-width: 400px;">
            <div class="text-center mb-6">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-primary);">Login Inventaris</h1>
                <p style="color: var(--color-text-muted);">Masuk untuk mengelola sarana sekolah</p>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" class="form-input" value="{{ old('email') }}" required
                        autofocus placeholder="nama@sekolah.id">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-input" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="ph ph-sign-in" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                    Masuk Sistem
                </button>
            </form>

            <div class="text-center" style="margin-top: 1.5rem; font-size: 0.875rem; color: var(--color-text-muted);">
                Sistem Inventaris Sarana dan Prasarana Sekolah
            </div>
        </div>
    </div>
@endsection