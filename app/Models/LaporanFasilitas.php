<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanFasilitas extends Model
{
    use HasFactory;

    protected $table = 'laporan_fasilitas';
    protected $primaryKey = 'id_laporan_fasilitas';
    protected $fillable = [
        'id_laporan',
        'id_fasilitas',
        'id_status',
        'id_tingkat_kerusakan',
        'id_dampak_pengguna',
        'path_foto',
        'deskripsi',
        'is_active',
        'created_at',
        'updated_at',
    ];

    public function laporan(): BelongsTo
    {
        return $this->belongsTo(Laporan::class, 'id_laporan', 'id_laporan');
    }


    public function fasilitas(): BelongsTo
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'id_status');
    }

    public function pelaporLaporanFasilitas(): HasMany
    {
        return $this->hasMany(PelaporLaporanFasilitas::class, 'id_laporan_fasilitas');
    }

    public function riwayatLaporanFasilitas(): HasMany
    {
        return $this->hasMany(RiwayatLaporanFasilitas::class, 'id_laporan_fasilitas');
    }

    public function penugasan(): HasOne
    {
        return $this->hasOne(Penugasan::class, 'id_laporan_fasilitas');
    }

    public function penilaian(): HasMany
    {
        return $this->hasMany(Penilaian::class, 'id_laporan_fasilitas');
    }

    public function skorTopsis(): HasMany
    {
        return $this->hasMany(SkorTopsis::class, 'id_laporan_fasilitas');
    }

    // app/Models/LaporanFasilitas.php

/** Koreksi relasi: gunakan kunci id_tingkat_kerusakan **/
    public function tingkatKerusakan(): BelongsTo
    {
        return $this->belongsTo(SkoringKriteria::class, 'id_tingkat_kerusakan', 'id_skoring_kriteria');
    }

    /** Koreksi relasi: gunakan kunci id_dampak_pengguna **/
    public function dampakPengguna(): BelongsTo
    {
        return $this->belongsTo(SkoringKriteria::class, 'id_dampak_pengguna', 'id_skoring_kriteria');
    }

    public function penilaianPengguna(): HasOne
{
    return $this->hasOne(PenilaianPengguna::class, 'id_laporan_fasilitas');
}

}
