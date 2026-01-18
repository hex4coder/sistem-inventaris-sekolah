<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        return view('backup.index');
    }

    public function download()
    {
        $filename = 'backup-' . now()->format('Y-m-d-H-i-s') . '.zip';
        $zipPath = storage_path('app/public/' . $filename);
        $sqlPath = storage_path('app/public/database.sql');

        // 1. Generate Database Dump
        $dbConfig = config('database.connections.mysql');
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['host'],
            $dbConfig['database'],
            $sqlPath
        );

        $returnVar = null;
        $output = null;
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            return back()->with('error', 'Gagal membuat backup database.');
        }

        // 2. Create ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
            // Add Database Dump
            $zip->addFile($sqlPath, 'database.sql');

            // Add Storage Files (public folder)
            $files = File::allFiles(storage_path('app/public'));
            foreach ($files as $file) {
                // Skip the zip itself and the sql dump
                if ($file->getFilename() === $filename || $file->getFilename() === 'database.sql') {
                    continue;
                }

                $relativePath = 'storage/' . $file->getRelativePathname();
                $zip->addFile($file->getRealPath(), $relativePath);
            }

            $zip->close();
        }

        // 3. Cleanup SQL Dump
        unlink($sqlPath);

        // 4. Download and Delete ZIP after send
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip',
        ]);

        $zipPath = $request->file('backup_file')->getPathname();
        $extractPath = storage_path('app/temp_restore');

        if (!File::exists($extractPath)) {
            File::makeDirectory($extractPath);
        }

        // 1. Extract ZIP
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return back()->with('error', 'Gagal membuka file backup.');
        }

        // 2. Restore Database
        $sqlPath = $extractPath . '/database.sql';
        if (File::exists($sqlPath)) {
            $dbConfig = config('database.connections.mysql');
            $command = sprintf(
                'mysql --user=%s --password=%s --host=%s %s < %s',
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['host'],
                $dbConfig['database'],
                $sqlPath
            );

            exec($command);
        }

        // 3. Restore Files
        $storagePath = $extractPath . '/storage';
        if (File::exists($storagePath)) {
            File::copyDirectory($storagePath, storage_path('app/public'));
        }

        // 4. Cleanup
        File::deleteDirectory($extractPath);

        return back()->with('success', 'Sistem berhasil direstore sepenuhnya.');
    }
}
