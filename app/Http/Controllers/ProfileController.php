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

    public function delete_photo(Request $request)
    {
        $user = Pengguna::find(Auth::id());

        if ($user->foto_profile) {
            // Hapus file dari storage
            Storage::delete('public/uploads/profiles/' . $user->foto_profile);

            // Hapus nama file di DB
            $user->foto_profile = null;
            $user->save();

            return redirect()->back()->with('success', 'Foto profil berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    public function update_info(Request $request)
    {
        $request->validate([
            'username'       => 'required|string|max:255',
            'nama'           => 'required|string|max:255',
        ]);

        $user = Pengguna::find(Auth::id());
        $changed = false;

        // 1. Ubah username/nama
        if ($request->username !== $user->username) {
            $user->username = $request->username;
            $changed = true;
        }
        if ($request->nama !== $user->nama) {
            $user->nama = $request->nama;
            $changed = true;
        }

        // 2. Ubah password, hanya kalau field new_password diisi
        if ($request->filled('new_password')) {
        $request->validate([
                'old_password' => 'required',
                'new_password'     => 'required|min:5|confirmed',
            ]);

            $user = Pengguna::find(Auth::id());

            if (!Hash::check($request->old_password, $user->password)) {
                return back()->withErrors(['old_password' => 'Password lama tidak sesuai']);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Password berhasil diubah.');
        }

        if (! $changed) {
            // Tidak ada perubahan apa-apa
            return redirect()->back();
        }

        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
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
