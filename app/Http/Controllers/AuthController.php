<?php

namespace App\Http\Controllers;

use App\Models\Peran;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\NoIndukVerifierService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    protected $noIndukVerifier;

    public function __construct(NoIndukVerifierService $noIndukVerifier)
    {
        $this->noIndukVerifier = $noIndukVerifier;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            $data = $request->validate([
                'no_induk' => 'required|string|max:20|unique:pengguna,no_induk',
                'nama' => 'required|string|max:40',
                'username' => 'required|string|max:20|unique:pengguna,username',
                'password' => 'required|string|confirmed|min:5',
            ]);

            // Verifikasi format nomor induk menggunakan service
            $verificationResult = $this->noIndukVerifier->verify($data['no_induk']);

            // Jika format tidak valid atau service error apa pun, lempar pesan umum
            if (
                $verificationResult['type'] === 'Tidak Valid' ||
                $verificationResult['type'] === 'Tidak Diketahui' ||
                !empty($verificationResult['errors'])
            ) {
                throw ValidationException::withMessages([
                    'no_induk' => ['Nomor induk tidak valid']
                ]);
            }

            $roleId = Peran::where('kode_peran', 'GST')->value('id_peran');

            Pengguna::create([
                'id_peran' => $roleId,
                'no_induk' => $data['no_induk'],
                'nama' => $data['nama'],
                'username' => $data['username'],
                'password' => $data['password'],
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                'redirect' => url('/login')
            ]);
        } catch (\Exception $e) {
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
            'redirect' => route('dashboard'),
        ]);
    }

    return response()->json([
        'status' => false,
         'message'  => 'Username atau password salah',
         'errors'   => [
            'username' => ['Username atau password salah'],
            'password' => ['Username atau password salah'],
        ],
    ], 200);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing.index')->with('success', 'Anda telah berhasil logout');
    }
}
