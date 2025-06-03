<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PenilaianPengguna extends Model
{
    use HasFactory;

    protected $table = 'penilaian_pengguna';
    protected $primaryKey = 'id_penilaian_pengguna';
    protected $fillable = [
        'id_laporan_fasilitas',
        'id_pengguna',
        'nilai',
        'komentar',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'id_laporan_fasilitas');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
