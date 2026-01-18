<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,staff,guru',
        ]);

        \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(\App\Models\User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(\Illuminate\Http\Request $request, \App\Models\User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,staff,guru',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(\App\Models\User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function import()
    {
        return view('users.import');
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users_template.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['name', 'email', 'password', 'role']);
            fputcsv($file, ['John Doe', 'john@example.com', 'password123', 'staff']);
            fputcsv($file, ['Jane Admin', 'jane@example.com', 'securepass', 'admin']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function processImport(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getPathname(), 'r');

        // Skip header row
        fgetcsv($handle);

        $row = 1;
        $successCount = 0;
        $errors = [];

        while (($data = fgetcsv($handle)) !== false) {
            $row++;

            // Basic validation for row counts
            if (count($data) < 4) {
                $errors[] = "Baris $row: Data tidak lengkap.";
                continue;
            }

            [$name, $email, $password, $role] = $data;

            // Validate email uniqueness
            if (\App\Models\User::where('email', $email)->exists()) {
                $errors[] = "Baris $row: Email $email sudah terdaftar.";
                continue;
            }

            // Validate role
            if (!in_array($role, ['admin', 'staff', 'guru'])) {
                $errors[] = "Baris $row: Role tidak valid ($role).";
                continue;
            }

            try {
                \App\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => \Illuminate\Support\Facades\Hash::make($password),
                    'role' => $role,
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = "Baris $row: Gagal menyimpan data. " . $e->getMessage();
            }
        }

        fclose($handle);

        if (count($errors) > 0) {
            return redirect()->route('users.import')->with('error', "Import selesai dengan catatan: " . count($errors) . " error. $successCount berhasil.")->with('import_errors', $errors);
        }

        return redirect()->route('users.index')->with('success', "$successCount pengguna berhasil diimport.");
    }
}
