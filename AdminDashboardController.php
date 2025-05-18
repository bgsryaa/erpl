<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // pastikan model User di-import

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all(); // Ambil semua data user
        return view('admin.admin-dashboard', compact('users'));
    }
}