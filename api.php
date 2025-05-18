<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;

// Endpoint login (tidak perlu auth)
Route::post('/login', [AuthController::class, 'login']);

// Group route yang butuh autentikasi Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Melihat semua absensi (admin)
    Route::get('/attendance', [AttendanceController::class, 'index']);
    // Mengisi absensi (user)
    Route::post('/attendance', [AttendanceController::class, 'store']);
});