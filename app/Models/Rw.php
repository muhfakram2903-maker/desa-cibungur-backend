<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rw extends Model
{
    protected $table = 'rw';

    protected $fillable = [
        'nomor',
        'dusun_id',
        'ketua_nama',
        'ketua_hp',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function rt(): HasMany
    {
        return $this->hasMany(Rt::class);
    }

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessor
    public function getNomorLengkapAttribute(): string
    {
        return 'RW ' . str_pad($this->nomor, 2, '0', STR_PAD_LEFT);
    }
}
