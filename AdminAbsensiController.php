<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;

class AdminAbsensiController extends Controller
{
    // Daftar absensi (semua user)
    public function index()
    {
        $absensi = Absensi::with('user')->orderBy('created_at', 'desc')->get();
        return view('admin.attendance', compact('absensi'));
    }

    // Detail absensi (per id)
    public function show($id)
    {
        $absen = Absensi::with('user')->findOrFail($id);
        return view('admin.absensi_detail', compact('absen'));
    }
}