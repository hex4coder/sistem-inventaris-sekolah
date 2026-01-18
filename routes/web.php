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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::resource('items', \App\Http\Controllers\ItemController::class)->middleware('auth');
Route::resource('borrowings', \App\Http\Controllers\BorrowingController::class)->middleware('auth');

Route::middleware(['auth', \App\Http\Middleware\EnsureAdmin::class])->group(function () {
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    Route::resource('locations', \App\Http\Controllers\LocationController::class);
    Route::get('users/template', [\App\Http\Controllers\UserController::class, 'downloadTemplate'])->name('users.template');
    Route::get('users/import', [\App\Http\Controllers\UserController::class, 'import'])->name('users.import');
    Route::post('users/import', [\App\Http\Controllers\UserController::class, 'processImport'])->name('users.process_import');
    Route::resource('users', \App\Http\Controllers\UserController::class);
});
