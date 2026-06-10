<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\IkkController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login']);
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

// Back Office Routes (Protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Dashboard Overview
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Anggota (Members) Management & Verification
    Route::get('/anggota/export', [AnggotaController::class, 'exportCsv'])->name('admin.anggota.export');
    Route::get('/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
    Route::get('/anggota/{id}', [AnggotaController::class, 'show'])->name('admin.anggota.show');
    Route::get('/anggota/{id}/edit', [AnggotaController::class, 'edit'])->name('admin.anggota.edit');
    Route::put('/anggota/{id}', [AnggotaController::class, 'update'])->name('admin.anggota.update');
    Route::post('/anggota/{id}/verify-ktp', [AnggotaController::class, 'verifyKtp'])->name('admin.anggota.verify_ktp');
    Route::post('/anggota/{id}/suspend', [AnggotaController::class, 'suspend'])->name('admin.anggota.suspend');

    // IKK (Ikatan Keluarga Kabupaten/Kota) CRUD
    Route::get('/ikk', [IkkController::class, 'index'])->name('admin.ikk.index');
    Route::get('/ikk/create', [IkkController::class, 'create'])->name('admin.ikk.create');
    Route::post('/ikk', [IkkController::class, 'store'])->name('admin.ikk.store');
    Route::get('/ikk/{id}/edit', [IkkController::class, 'edit'])->name('admin.ikk.edit');
    Route::put('/ikk/{id}', [IkkController::class, 'update'])->name('admin.ikk.update');
    Route::delete('/ikk/{id}', [IkkController::class, 'destroy'])->name('admin.ikk.destroy');
});
