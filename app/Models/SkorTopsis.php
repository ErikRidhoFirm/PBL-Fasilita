<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkorTopsis extends Model
{
    use HasFactory;

    protected $table = 'skor_topsis';
    protected $primaryKey = 'id_skor_topsis';
    protected $fillable = [
        'id_skor_tipe',
        'id_laporan_fasilitas',
        'skor',
    ];

    public function skorTipe(): BelongsTo
    {
        return $this->belongsTo(SkorTipe::class, 'id_skor_tipe');
    }

    public function laporanFasilitas(): BelongsTo
    {
        return $this->belongsTo(LaporanFasilitas::class, 'id_laporan_fasilitas');
    }
}
