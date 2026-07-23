<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rt extends Model
{
    protected $table = 'rt';

    protected $fillable = [
        'nomor',
        'rw_id',
        'ketua_nama',
        'ketua_hp',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
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
        return 'RT ' . str_pad($this->nomor, 2, '0', STR_PAD_LEFT);
    }

    public function getLokasiAttribute(): string
    {
        return $this->nomor_lengkap . ' / ' . ($this->rw->nomor_lengkap ?? '');
    }
}
