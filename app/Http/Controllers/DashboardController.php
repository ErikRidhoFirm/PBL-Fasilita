<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\Pengguna $user */
        $user = Auth::user();

        if ($user->hasAnyRole(['MHS', 'DSN', 'TDK'])) {
            return redirect()->route('dashboard-pelapor.index');
        }

        

        return view('dashboard.index');
    }
}
