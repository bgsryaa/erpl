<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use Illuminate\Support\Facades\Auth;

class AbsensiController extends Controller
{
    // Tampilkan form absensi
    public function showForm()
    {
        return view('absensi');
    }

    // Proses submit absensi
    public function submit(Request $request)
    {
        $request->validate([
            'barcode' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        Absensi::create([
            'user_id' => Auth::id(),
            'barcode' => $request->barcode,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil!');
    }
}