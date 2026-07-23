<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pendidikan extends Model
{
    protected $table = 'pendidikan';
    protected $fillable = ['nama', 'urutan'];

    protected $casts = ['urutan' => 'integer'];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('urutan');
    }
}
