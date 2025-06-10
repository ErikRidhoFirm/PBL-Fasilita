<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penugasan extends Model
{
    use HasFactory;

    protected $table = 'penugasan';
    protected $primaryKey = 'id_penugasan';
    protected $fillable = [
        'id_laporan_fasilitas',
        'id_pengguna', // id teknisi
        'is_complete',
    ];

    public function laporanFasilitas(): BelongsTo
    {
        return $this->belongsTo(LaporanFasilitas::class, 'id_laporan_fasilitas');
    }

    public function teknisi(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function perbaikan()
    {
        return $this->hasOne(Perbaikan::class, 'id_penugasan');
    }

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }
}
