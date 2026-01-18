@extends('layouts.app')

@section('content')
    <div class="container" style="padding-top: 2rem;">
        <div class="card">
            <h2 class="mb-4">Selamat Datang di Dashboard</h2>
            <p>Anda login sebagai role: <strong>{{ Auth::user()->role }}</strong></p>

            <div
                style="margin-top: 2rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h3
                        style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                        Total Barang</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: var(--color-primary);">
                        {{ \App\Models\Item::count() }}</p>
                </div>

                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <h3
                        style="font-size: 0.875rem; color: var(--color-text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                        Peminjaman Aktif</h3>
                    <p style="font-size: 2rem; font-weight: 700; color: #f59e0b;">
                        {{ \App\Models\Borrowing::where('status', 'approved')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
