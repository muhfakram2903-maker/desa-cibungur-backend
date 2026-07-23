<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Penduduk extends Model
{
    use SoftDeletes;

    protected $table = 'penduduk';

    protected $fillable = [
        'nik',
        'nomor_kk',
        'nama',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'dusun_id',
        'rw_id',
        'rt_id',
        'agama_id',
        'pendidikan_id',
        'pekerjaan_id',
        'status_kawin',
        'status_keluarga',
        'no_hp',
        'email',
        'foto',
        'status_aktif',
        'tanggal_masuk',
        'tanggal_keluar',
        'alasan_keluar',
        'user_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_lahir'   => 'date',
        'tanggal_masuk'   => 'date',
        'tanggal_keluar'  => 'date',
        'status_aktif'    => 'boolean',
    ];

    protected $appends = ['umur', 'nama_lengkap_nik'];

    // Relations
    public function dusun(): BelongsTo
    {
        return $this->belongsTo(Dusun::class);
    }

    public function rw(): BelongsTo
    {
        return $this->belongsTo(Rw::class);
    }

    public function rt(): BelongsTo
    {
        return $this->belongsTo(Rt::class);
    }

    public function agama(): BelongsTo
    {
        return $this->belongsTo(Agama::class);
    }

    public function pendidikan(): BelongsTo
    {
        return $this->belongsTo(Pendidikan::class);
    }

    public function pekerjaan(): BelongsTo
    {
        return $this->belongsTo(Pekerjaan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pembuatData(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function riwayat(): HasMany
    {
        return $this->hasMany(PendudukRiwayat::class);
    }

    // Accessors
    public function getUmurAttribute(): ?int
    {
        if (!$this->tanggal_lahir) return null;
        return $this->tanggal_lahir->age;
    }

    public function getNamaLengkapNikAttribute(): string
    {
        return $this->nama . ' (' . $this->nik . ')';
    }

    public function getFotoUrlAttribute(): string
    {
        if ($this->foto) {
            return asset('storage/' . $this->foto);
        }
        return asset('images/default-avatar.png');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function scopeLakiLaki($query)
    {
        return $query->where('jenis_kelamin', 'L');
    }

    public function scopePerempuan($query)
    {
        return $query->where('jenis_kelamin', 'P');
    }

    public function scopeByDusun($query, int $dusunId)
    {
        return $query->where('dusun_id', $dusunId);
    }

    public function scopeByRw($query, int $rwId)
    {
        return $query->where('rw_id', $rwId);
    }

    public function scopeByRt($query, int $rtId)
    {
        return $query->where('rt_id', $rtId);
    }

    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama', 'like', "%{$keyword}%")
              ->orWhere('nik', 'like', "%{$keyword}%")
              ->orWhere('nomor_kk', 'like', "%{$keyword}%");
        });
    }
}
