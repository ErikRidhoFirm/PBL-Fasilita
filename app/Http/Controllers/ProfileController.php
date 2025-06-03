<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class ProfileController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Profil User',
            'list'  => ['Home', 'Profil']
        ];

        $page = (object) [
            'title' => 'Profil user yang sedang login'
        ];

        $activeMenu = 'profile'; // untuk menandai menu aktif di sidebar

        $user = Pengguna::find(Auth::id());

        return view('profile.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update_photo(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Pengguna::find(Auth::id());

        if (! $request->hasFile('foto')) {
            return redirect()->back();
        }

        // Hapus foto lama jika ada
        if ($user->foto_profile) {
            Storage::delete('public/uploads/profiles/' . $user->foto_profile);
        }

        // Simpan foto baru
        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();

        // Simpan file ke storage
        $path = $file->storeAs('public/uploads/profiles', $filename);

        // Simpan nama file ke database (hanya nama file, tanpa path)
        $user->foto_profile = $filename;
        $user->save();

        return redirect()->back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function update_info(Request $request)
    {
        $request->validate([
            'username'      => 'required|string|max:255',
            'nama'          => 'required|string|max:255',
            'new_password'  => 'nullable|min:5',
            'confirm_password'  => 'required|min:5|same:new_password',
        ]);

        $user = Pengguna::find(Auth::id());

        $ubah = false;

        if ($request->username !== $user->username) {
            $user->username = $request->username;
            $ubah = true;
        }

        if ($request->nama !== $user->nama) {
            $user->nama = $request->nama;
            $ubah = true;
        }

        if ($request->filled('new_password')) {
            $user->password = bcrypt($request->new_password);
            $ubah = true;
        }

        if (! $ubah) {
            return redirect()->back();
        }

        $user->save();

        return redirect()->back()->with('success', 'Biodata berhasil diperbarui.');
    }

    public function update_password(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Pengguna::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }

    public function edit()
{
    $breadcrumb = (object) [
        'title' => 'Edit Profil',
        'list'  => ['Home', 'Profil', 'Edit']
    ];

    $page = (object) [
        'title' => 'Form Edit Profil'
    ];

    $activeMenu = 'profile';

    $user = Pengguna::find(Auth::id());

    return view('profile.edit', [
        'breadcrumb' => $breadcrumb,
        'page'       => $page,
        'user'       => $user,
        'activeMenu' => $activeMenu
    ]);
}

}
