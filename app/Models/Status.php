<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_status';
    protected $table = 'status';
    protected $fillable = [
        'nama_status',
    ];

    public const MENUNGGU                = 1;
    public const TIDAK_VALID       = 2;
    public const DITOLAK          = 3;
    public const VALID           = 4;
    public const DITUGASKAN                = 5;
    public const SELESAI                 = 6;
    public const DITUTUP                 = 7;

    public function laporan(): HasMany
    {
        return $this->hasMany(Laporan::class, 'id_status');
    }

    public function riwayatLaporan(): HasMany
    {
        return $this->hasMany(RiwayatLaporanFasilitas::class, 'id_status');
    }
}
