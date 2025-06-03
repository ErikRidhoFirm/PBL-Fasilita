<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'bobot_kriteria',
        'tipe_kriteria',
        'deskripsi',
    ];

    public function skorKriteriaLaporan(): HasMany
    {
        return $this->hasMany(SkorKriteriaLaporan::class, 'id_kriteria');
    }

    public function skoringKriterias(): HasMany
    {
        return $this->hasMany(SkoringKriteria::class, 'id_kriteria');
    }
}
