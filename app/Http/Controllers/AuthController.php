<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try{

            $data = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|unique:pengguna,username',
                'password' => 'required|string|confirmed|min:5',
            ]);
            
            $roleId = Peran::where('kode_peran', 'MHS')->value('id_peran');
            
            Pengguna::create([
                'id_peran' => $roleId,
                'nama' => $data['nama'],
                'username' => $data['username'],
                'password' => $data['password'],
                'foto_profile' => 'default.jpg', // ← default foto profil
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                // 'redirect' => url('/login')
            ]);
            return redirect('login');
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showLogin()
    {
            return view('auth.login');
    }

    public function login(Request $request)
{
    $c = $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    if (Auth::guard('web')->attempt($c, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return response()->json([
            'status'   => true,
            'message'  => 'Login Berhasil',
            // Arahkan ke route('dashboard') bukan url('/')
            'redirect' => route('dashboard'),
        ]);
    }

    return response()->json([
        'status' => false,
        'errors' => ['username' => 'Username atau password salah']
    ], 422);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing.index')->with('success', 'Anda telah berhasil logout');
    }
}
