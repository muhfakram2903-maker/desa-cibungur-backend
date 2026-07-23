<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dusun extends Model
{
    protected $table = 'dusun';

    protected $fillable = [
        'nama',
        'kode',
        'ketua_nama',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function rw(): HasMany
    {
        return $this->hasMany(Rw::class);
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    public function umkm(): HasMany
    {
        return $this->hasMany(Umkm::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
