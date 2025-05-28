<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkorTipe extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_skor_tipe';
    protected $table = 'skor_tipe';
    protected $fillable = [
        'tipe',
        'alt_count',
        'cri_count',
    ];


    public function skorTopsis(): HasMany
    {
        return $this->hasMany(SkorTopsis::class, 'id_skor_tipe');
    }
}
