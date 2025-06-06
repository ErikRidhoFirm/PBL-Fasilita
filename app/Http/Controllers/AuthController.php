<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|unique:pengguna,username',
            'password' => 'required|string|confirmed|min:5',
        ], [
            'username.unique' => 'Username sudah digunakan.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
                
            ]);
        }
        $data = $validator->validated();

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
            'redirect' => url('/login')
        ]);
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
            'message' => [
                'username' => 'Username salah',
                'password' => 'Password salah',
            ]
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
