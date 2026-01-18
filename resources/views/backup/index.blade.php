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

        <div class="card" style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text); margin-bottom: 1rem;">Backup & Restore
                Database</h2>
            <p style="color: var(--color-text-muted); margin-bottom: 2rem;">
                Unduh cadangan data lengkap (Database + File) atau pulihkan sistem dari file cadangan sebelumnya.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <!-- Backup Section -->
                <div style="background: #f8fafc; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e2e8f0;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #0f172a;">Backup Sistem
                    </h3>
                    <p style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #64748b;">
                        Unduh file ZIP yang berisi seluruh data database dan file yang diunggah. Simpan file ini di tempat
                        yang aman.
                    </p>

                    <form action="{{ route('backup.download') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="ph ph-download-simple" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                            Download Full Backup (.zip)
                        </button>
                    </form>
                </div>

                <!-- Restore Section -->
                <div style="background: #fff1f2; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #fecaca;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; margin-bottom: 1rem; color: #991b1b;">Restore Sistem
                        (Berbahaya)</h3>
                    <p style="margin-bottom: 1.5rem; font-size: 0.875rem; color: #7f1d1d;">
                        Pulihkan sistem dari file backup. <strong>PERINGATAN:</strong> Tindakan ini akan menimpa seluruh
                        data dan file saat ini. Data yang ada akan hilang permanen.
                    </p>

                    <form action="{{ route('backup.restore') }}" method="POST" enctype="multipart/form-data"
                        onsubmit="return confirm('APAKAH ANDA YAKIN? Semua data saat ini akan DITIMPA dan HILANG. Tindakan ini tidak dapat dibatalkan.');">
                        @csrf
                        <div class="form-group">
                            <input type="file" name="backup_file" class="form-input" required accept=".zip"
                                style="background: white;">
                        </div>
                        <button type="submit" class="btn"
                            style="width: 100%; background: #dc2626; color: white; border: none;">
                            <i class="ph ph-upload-simple" style="margin-right: 0.5rem; font-size: 1.25rem;"></i>
                            Upload & Restore Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection