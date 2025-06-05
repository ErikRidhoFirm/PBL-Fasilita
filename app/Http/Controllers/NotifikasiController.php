<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan daftar notifikasi dengan pagination dan filter
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Hitung jumlah untuk setiap kategori
        $totalAll    = $user->notifikasi()->count();
        $totalUnread = $user->notifikasi()->where('is_read', false)->count();
        $totalRead   = $user->notifikasi()->where('is_read', true)->count();

        // Ambil filter dari query string: 'all' (default), 'unread', 'read'
        $filter = $request->get('filter', 'all');

        // Bangun query dasar
        $query = $user->notifikasi()
                      ->with(['laporanFasilitas.fasilitas', 'laporanFasilitas.status'])
                      ->orderByDesc('created_at');

        // Terapkan kondisi filter
        if ($filter === 'unread') {
            $query->where('is_read', false);
        } elseif ($filter === 'read') {
            $query->where('is_read', true);
        }
        // Jika 'all', tidak perlu where tambahan

        // Paginate (10 per halaman), dan sertakan query string pada link
        $notifikasi = $query->paginate(5)->withQueryString();

        return view('notifikasi.index', compact(
            'notifikasi',
            'totalAll',
            'totalUnread',
            'totalRead',
            'filter'
        ));
    }

    /**
     * Tandai satu notifikasi sebagai sudah dibaca
     */
    public function markAsRead($id_notifikasi)
    {
        try {
            $notif = Notifikasi::where('id_notifikasi', $id_notifikasi)
                               ->where('id_pengguna', Auth::id())
                               ->firstOrFail();

            if (! $notif->is_read) {
                $notif->update(['is_read' => true]);
                return redirect()->back()->with('success', 'Notifikasi telah ditandai sebagai dibaca.');
            }

            return redirect()->back();

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        }
    }

    /**
     * Tandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        try {
            $updatedCount = Notifikasi::where('id_pengguna', Auth::id())
                                     ->where('is_read', false)
                                     ->update(['is_read' => true]);

            if ($updatedCount > 0) {
                return redirect()->back()->with('success', "Berhasil menandai {$updatedCount} notifikasi sebagai dibaca.");
            }

            return redirect()->back()->with('info', 'Tidak ada notifikasi yang perlu ditandai.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui notifikasi.');
        }
    }

    public function show($id_notifikasi)
    {
        // Pastikan hanya pemilik notifikasi yang bisa melihat detail
        $item = Notifikasi::where('id_notifikasi', $id_notifikasi)
                          ->where('id_pengguna', Auth::id())
                          ->with(['laporanFasilitas.fasilitas', 'laporanFasilitas.status'])
                          ->firstOrFail();

        return view('notifikasi.show', compact('item'));
    }

    /**
     * Hapus notifikasi
     */
    public function destroy($id_notifikasi)
    {
        try {
            $notif = Notifikasi::where('id_notifikasi', $id_notifikasi)
                               ->where('id_pengguna', Auth::id())
                               ->firstOrFail();

            $notif->delete();
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan.');
        }
    }
}
