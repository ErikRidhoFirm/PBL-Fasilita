<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;

    protected $table = 'notifikasi';

    protected $primaryKey = 'id_notifikasi';

    protected $fillable = [
        'id_pengguna',
        'id_laporan_fasilitas',
        'judul',
        'pesan',
        'is_read',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
    
    public function laporanFasilitas()
    {
        return $this->belongsTo(LaporanFasilitas::class, 'id_laporan_fasilitas');
    }
}
