<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        return view('settings.index', compact('settings'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:20',
            'semester' => 'required|string|max:20',
            'school_logo' => 'nullable|image|max:2048',
            'sarpras_name' => 'nullable|string|max:255',
            'sarpras_nip' => 'nullable|string|max:255',
        ]);

        \App\Models\Setting::updateOrCreate(['key' => 'school_name'], ['value' => $request->school_name]);
        \App\Models\Setting::updateOrCreate(['key' => 'academic_year'], ['value' => $request->academic_year]);
        \App\Models\Setting::updateOrCreate(['key' => 'semester'], ['value' => $request->semester]);
        \App\Models\Setting::updateOrCreate(['key' => 'sarpras_name'], ['value' => $request->sarpras_name]);
        \App\Models\Setting::updateOrCreate(['key' => 'sarpras_nip'], ['value' => $request->sarpras_nip]);

        if ($request->hasFile('school_logo')) {
            $path = $request->file('school_logo')->store('logos', 'public');
            \App\Models\Setting::updateOrCreate(['key' => 'school_logo'], ['value' => $path]);
        }

        return back()->with('success', 'Pengaturan sekolah berhasil diperbarui.');
    }


}
