<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'nomor_tiket',
        'user_id',
        'nama_pelapor',
        'hp_pelapor',
        'email_pelapor',
        'judul',
        'kategori_id',
        'lokasi',
        'lat',
        'lng',
        'deskripsi',
        'foto',
        'video',
        'status',
        'prioritas',
        'ditangani_oleh',
        'catatan_admin',
    ];

    protected $casts = [
        'foto' => 'array',
        'lat'  => 'float',
        'lng'  => 'float',
    ];

    // Auto-generate nomor tiket
    protected static function booted(): void
    {
        static::creating(function ($pengaduan) {
            if (empty($pengaduan->nomor_tiket)) {
                $pengaduan->nomor_tiket = self::generateNomorTiket();
            }
        });

        static::updating(function ($pengaduan) {
            if ($pengaduan->isDirty('status')) {
                // Create timeline entry saat status berubah
                $pengaduan->timeline()->create([
                    'user_id'     => auth()->id(),
                    'status_lama' => $pengaduan->getOriginal('status'),
                    'status_baru' => $pengaduan->status,
                ]);
            }
        });
    }

    public static function generateNomorTiket(): string
    {
        $prefix = 'PCB'; // Pengaduan Cibungur
        $tahun  = date('Y');
        $bulan  = date('m');
        $count  = self::whereYear('created_at', $tahun)->whereMonth('created_at', $bulan)->count() + 1;
        return $prefix . $tahun . $bulan . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_id');
    }

    public function penanggungjawab(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditangani_oleh');
    }

    public function respon(): HasMany
    {
        return $this->hasMany(PengaduanRespon::class);
    }

    public function responPublik(): HasMany
    {
        return $this->hasMany(PengaduanRespon::class)->where('is_public', true);
    }

    public function timeline(): HasMany
    {
        return $this->hasMany(PengaduanTimeline::class)->orderBy('created_at');
    }

    // Scopes
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }

    // Accessors
    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => '<span class="badge bg-warning">Menunggu</span>',
            'diproses'  => '<span class="badge bg-info">Diproses</span>',
            'selesai'   => '<span class="badge bg-success">Selesai</span>',
            'ditolak'   => '<span class="badge bg-danger">Ditolak</span>',
            default     => '<span class="badge bg-secondary">-</span>',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'menunggu'  => 'warning',
            'diproses'  => 'info',
            'selesai'   => 'success',
            'ditolak'   => 'danger',
            default     => 'secondary',
        };
    }

    public function getNamaPelaporDisplayAttribute(): string
    {
        return $this->user?->name ?? $this->nama_pelapor ?? 'Anonim';
    }
}
