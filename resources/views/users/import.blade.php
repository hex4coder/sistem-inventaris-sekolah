@extends('layouts.app')

@section('content')
    <div class="container" style="max-width: 600px; padding-top: 2rem; padding-bottom: 2rem;">
        <div class="card">
            <div class="mb-6 flex justify-between items-center">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--color-text);">Import Pengguna</h1>
                <a href="{{ route('users.index') }}"
                    style="color: var(--color-text-muted); text-decoration: none; display: inline-flex; align-items: center;">
                    <i class="ph ph-arrow-left" style="margin-right: 0.5rem;"></i>
                    Kembali
                </a>
            </div>

            @if(session('error'))
                <div
                    style="background-color: #fef2f2; border-left: 4px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem;">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('import_errors'))
                <div
                    style="background-color: #fef2f2; border: 1px solid #fee2e2; padding: 1rem; border-radius: 0.25rem; margin-bottom: 1.5rem; max-height: 200px; overflow-y: auto;">
                    <p style="font-weight: 600; color: #991b1b; margin-bottom: 0.5rem;">Detail Error:</p>
                    <ul style="list-style: disc; padding-left: 1.5rem; color: #b91c1c;">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert-info"
                style="margin-bottom: 1.5rem; background-color: #eff6ff; border-left: 4px solid #3b82f6; color: #1e40af; padding: 1rem; border-radius: 0.25rem;">
                <p style="margin-bottom: 0.5rem;"><strong>Format CSV:</strong></p>
                <p>Gunakan file CSV dengan kolom: <code>name</code>, <code>email</code>, <code>password</code>,
                    <code>role</code>.</p>
                <a href="{{ route('users.template') }}"
                    style="color: #2563eb; text-decoration: underline; font-weight: 500;">
                    <i class="ph ph-download-simple"></i> Download Template CSV
                </a>
            </div>

            <form method="POST" action="{{ route('users.process_import') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="csv_file" class="form-label">Upload File CSV</label>
                    <input type="file" name="csv_file" id="csv_file" class="form-input" accept=".csv" required>
                </div>

                <div class="flex justify-end gap-4" style="margin-top: 1rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-upload-simple" style="margin-right: 0.5rem;"></i> Proses Import
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection