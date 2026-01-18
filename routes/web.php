<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware('auth')->name('dashboard');

Route::resource('items', \App\Http\Controllers\ItemController::class)->middleware('auth');
Route::get('borrowings/export/pdf', [\App\Http\Controllers\BorrowingController::class, 'exportPdf'])->name('borrowings.export.pdf')->middleware('auth');
Route::get('borrowings/export/csv', [\App\Http\Controllers\BorrowingController::class, 'exportCsv'])->name('borrowings.export.csv')->middleware('auth');
Route::resource('borrowings', \App\Http\Controllers\BorrowingController::class)->middleware('auth');

Route::middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('locations', \App\Http\Controllers\LocationController::class);
    Route::get('users/template', [\App\Http\Controllers\UserController::class, 'downloadTemplate'])->name('users.template');
    Route::get('users/import', [\App\Http\Controllers\UserController::class, 'import'])->name('users.import');
    Route::post('users/import', [\App\Http\Controllers\UserController::class, 'processImport'])->name('users.processImport');
    Route::resource('users', \App\Http\Controllers\UserController::class);

    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    Route::get('backup', [\App\Http\Controllers\BackupController::class, 'index'])->name('backup.index');
    Route::post('backup/download', [\App\Http\Controllers\BackupController::class, 'download'])->name('backup.download');
    Route::post('backup/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('backup.restore');
});
