@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem; padding-bottom: 2rem;">
        @if(session('success'))
            <div
                style="background-color: #f0fdf4; border-left: 4px solid #4ade80; color: #166534; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                style="background-color: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Manajemen Pengguna</h2>
                    <p style="color: var(--color-text-muted);">Kelola akses pengguna aplikasi</p>
                </div>

                <a href="{{ route('users.create') }}" class="btn btn-primary">
                    <i class="ph ph-plus" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                    Tambah Pengguna
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Nama</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Email</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Role</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Bergabung</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 1rem; font-weight: 500;">
                                    <div class="flex items-center gap-3">
                                        <div
                                            style="width: 32px; height: 32px; background: #e2e8f0; border-radius: 50%; display: flex; items-center; justify-center; color: #64748b; font-weight: 600; font-size: 0.75rem; display: flex; align-items: center; justify-content: center;">
                                            {{ substr($user->name, 0, 2) }}
                                        </div>
                                        {{ $user->name }}
                                    </div>
                                </td>
                                <td style="padding: 1rem;">{{ $user->email }}</td>
                                <td style="padding: 1rem;">
                                    @if($user->isAdmin())
                                        <span
                                            style="background: #e0e7ff; color: #4338ca; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">ADMIN</span>
                                    @else
                                        <span
                                            style="background: #f1f5f9; color: #475569; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">STAFF</span>
                                    @endif
                                </td>
                                <td style="padding: 1rem; color: var(--color-text-muted); font-size: 0.875rem;">
                                    {{ $user->created_at->format('d M Y') }}
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="flex gap-4">
                                        <a href="{{ route('users.edit', $user) }}"
                                            style="color: #ea580c; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                            <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: var(--color-text-muted);">Belum
                                    ada data pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection