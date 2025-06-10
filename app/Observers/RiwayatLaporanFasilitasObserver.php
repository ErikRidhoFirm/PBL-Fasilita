<?php

namespace App\Observers;

use App\Models\Status;
use App\Models\Notifikasi;
use App\Models\RiwayatLaporanFasilitas;

class RiwayatLaporanFasilitasObserver
{
    /**
     * Handle the RiwayatLaporanFasilitas "created" event.
     */
    public function created(RiwayatLaporanFasilitas $riwayat): void
    {
        // Ambil status dari entri riwayat
        $statusId = $riwayat->id_status;

        if ($statusId == Status::MENUNGGU) {
            return;
        }

        // Ambil data parent LaporanFasilitas dan Laporannya
        $lf = $riwayat->laporanFasilitas;
        if (!$lf) {
            return; // Relasi tidak ditemukan, skip untuk menghindari error
        }

        $laporan = $lf->laporan;
        if (!$laporan) {
            return; // Relasi laporan tidak ditemukan, skip
        }

        $pelapor = $laporan->id_pengguna;
        if (!$pelapor) {
            return; // Pelapor tidak ditemukan, skip
        }

        $namaFas = $lf->fasilitas->nama_fasilitas ?? 'Fasilitas';
        $gedung = $laporan->gedung->nama_gedung ?? 'Gedung';
        $ruangan = $laporan->ruangan->nama_ruangan ?? 'Ruangan';

        switch ($statusId) {
            case Status::TIDAK_VALID:
                $judul = "Laporan {$namaFas} Tidak Valid";
                $pesan = "Laporan Anda untuk fasilitas “{$namaFas}” di ruangan “{$ruangan}” gedung “{$gedung}” tidak valid. Mohon perbaiki kembali. Terima kasih.";
                break;

            case Status::DITOLAK:
                $judul = "Laporan {$namaFas} Ditolak";
                $pesan = "Laporan Anda untuk fasilitas “{$namaFas}” di ruangan “{$ruangan}” gedung “{$gedung}” ditolak oleh tim verifikasi.";
                break;

            case Status::VALID:
                $judul = "Laporan {$namaFas} Valid";
                $pesan = "Laporan Anda untuk fasilitas “{$namaFas}” di ruangan “{$ruangan}” gedung “{$gedung}” telah dinyatakan valid dan akan segera kami proses. Terima kasih.";
                break;

            case Status::DITUGASKAN:
                $judul = "Laporan {$namaFas} Sedang Diperbaiki";
                $pesan = "Laporan Anda untuk fasilitas “{$namaFas}” di ruangan “{$ruangan}” gedung “{$gedung}” sudah ditugaskan ke teknisi dan sedang diperbaiki. Mohon menunggu selesainya perbaikan. Terima kasih.";
                break;

            case Status::SELESAI:
                $judul = "Laporan {$namaFas} Selesai Diperbaiki";
                $pesan = "Laporan Anda untuk fasilitas “{$namaFas}” di ruangan “{$ruangan}” gedung “{$gedung}” telah selesai diperbaiki. Terima kasih atas kesabarannya.";
                break;

            default:
                return; // Status tidak dikenali, skip
        }

        Notifikasi::create([
            'id_pengguna'          => $pelapor,
            'id_laporan_fasilitas' => $lf->id_laporan_fasilitas,
            'judul'                => $judul,
            'pesan'                => $pesan,
        ]);
    }

    /**
     * Handle the RiwayatLaporanFasilitas "updated" event.
     */
    public function updated(RiwayatLaporanFasilitas $riwayatLaporanFasilitas): void
    {
        //
    }

    /**
     * Handle the RiwayatLaporanFasilitas "deleted" event.
     */
    public function deleted(RiwayatLaporanFasilitas $riwayatLaporanFasilitas): void
    {
        //
    }

    /**
     * Handle the RiwayatLaporanFasilitas "restored" event.
     */
    public function restored(RiwayatLaporanFasilitas $riwayatLaporanFasilitas): void
    {
        //
    }

    /**
     * Handle the RiwayatLaporanFasilitas "force deleted" event.
     */
    public function forceDeleted(RiwayatLaporanFasilitas $riwayatLaporanFasilitas): void
    {
        //
    }
}
