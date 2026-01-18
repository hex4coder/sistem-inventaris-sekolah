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
                    <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Manajemen Kategori</h2>
                    <p style="color: var(--color-text-muted);">Kelola kategori barang inventaris</p>
                </div>

                <a href="{{ route('categories.create') }}" class="btn btn-primary">
                    <i class="ph ph-plus" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                    Tambah Kategori
                </a>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0;">
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">No</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Nama Kategori</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Deskripsi</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Jumlah Item</th>
                            <th style="padding: 1rem; font-weight: 600; color: var(--color-text-muted);">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                <td style="padding: 1rem; color: var(--color-text-muted);">
                                    {{ $categories->firstItem() + $index }}</td>
                                <td style="padding: 1rem; font-weight: 500;">{{ $category->name }}</td>
                                <td style="padding: 1rem;">{{ $category->description ?: '-' }}</td>
                                <td style="padding: 1rem;">
                                    <span
                                        style="background: #e0f2fe; color: #0369a1; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600;">
                                        {{ $category->items()->count() }} Item
                                    </span>
                                </td>
                                <td style="padding: 1rem;">
                                    <div class="flex gap-4">
                                        <a href="{{ route('categories.edit', $category) }}"
                                            style="color: #ea580c; text-decoration: none; font-weight: 500; display: inline-flex; align-items: center;">
                                            <i class="ph ph-pencil-simple" style="margin-right: 0.25rem;"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="padding: 2rem; text-align: center; color: var(--color-text-muted);">Belum
                                    ada data kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 2rem;">
                {{ $categories->links() }}
            </div>
        </div>
    </div>
@endsection