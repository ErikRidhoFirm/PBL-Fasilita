<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkorKriteriaLaporan extends Model
{
    use HasFactory;

    protected $table = 'skor_kriteria_laporan';
    protected $primaryKey = 'id_skor_kriteria_laporan';
    public $timestamps = true;
    protected $fillable = [
        'id_penilaian',
        'id_kriteria',
        'nilai_mentah',
    ];

    public function penilaian(): BelongsTo
    {
        return $this->belongsTo(Penilaian::class, 'id_penilaian');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria');
    }
}
