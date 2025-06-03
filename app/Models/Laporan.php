<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';
    protected $primaryKey = 'id_laporan';
    protected $fillable = [
        'id_pengguna',
        'id_gedung',
        'id_lantai',
        'id_ruangan',
        'created_at',
        'updated_at'
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    public function gedung(): BelongsTo
    {
        return $this->belongsTo(Gedung::class, 'id_gedung');
    }

    public function lantai(): BelongsTo
    {
        return $this->belongsTo(Lantai::class, 'id_lantai');
    }

    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }

    public function laporanFasilitas(): HasMany
    {
        return $this->hasMany(LaporanFasilitas::class, 'id_laporan', 'id_laporan');
    }

    public function penilaianPengguna(): HasMany
    {
        return $this->hasMany(PenilaianPengguna::class, 'id_laporan');
    }
}
