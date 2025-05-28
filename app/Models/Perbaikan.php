<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    use HasFactory;

    protected $table = 'perbaikan';
    protected $primaryKey = 'id_perbaikan';
    protected $fillable = [
        'id_penugasan',
        'foto_perbaikan',
        'jenis_perbaikan',
        'deskripsi_perbaikan',
    ];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class, 'id_penugasan');
    }
}
