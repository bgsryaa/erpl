<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminAbsensiController;
use App\Http\Controllers\AdminDashboardController;

Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
// Route halaman absensi mahasiswa
Route::get('/absensi', [AbsensiController::class, 'showForm'])->name('absensi.form');
Route::post('/absensi', [AbsensiController::class, 'submit'])->name('absensi.submit');

// Route halaman admin - daftar absensi
Route::get('/admin/absensi', [AdminAbsensiController::class, 'index'])->name('admin.absensi');
// Route halaman admin - detail absensi
Route::get('/admin/absensi/{id}', [AdminAbsensiController::class, 'show'])->name('admin.absensi.detail');

// Route logout (jika pakai Auth bawaan Laravel)
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');