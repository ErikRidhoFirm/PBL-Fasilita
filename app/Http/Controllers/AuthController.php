<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {

            $data = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|unique:pengguna,username',
                'password' => 'required|string|confirmed|min:5',
                'email' => 'required|string',
            ]);

            $roleId = Peran::where('kode_peran', 'MHS')->value('id_peran');

            Pengguna::create([
                'id_peran' => $roleId,
                'nama' => $data['nama'],
                'username' => $data['username'],
                'password' => $data['password'],
                'email' => $data['email'],
                'foto_profile' => 'default.jpg', // ← default foto profil
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                // 'redirect' => url('/login')
            ]);
            return redirect('login');
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/');
        } else {
            return view('auth.login');
        }
    }

    public function login(Request $request)
    {
        $c = $request->validate([
            'usernameOrEmail' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $c['usernameOrEmail'];
        $password = $c['password'];

        // Cek apakah yang dimasukkan adalah email atau username
        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $user = Pengguna::where($fieldType, $login)->first();

        // if (Auth::guard('web')->attempt($c, $request->boolean('remember'))) {
        //     $request->session()->regenerate();
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Login Berhasil',
        //         'redirect' => url('/')
        //     ]);
        // }

        if ($user && Hash::check($password, $user->password)) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return response()->json([
                'status' => true,
                'message' => 'Login Berhasil',
                'redirect' => url('/')
            ]);
        }

        return response()->json([
            'status' => false,
            'errors' => ['usernameOrEmail' => 'Username atau password salah']
        ], 422);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
