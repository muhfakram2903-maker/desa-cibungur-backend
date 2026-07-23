<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Berita extends Model
{
    use SoftDeletes;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'excerpt',
        'thumbnail',
        'kategori_id',
        'user_id',
        'status',
        'published_at',
        'view_count',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'is_featured',
        'allow_comment',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured'  => 'boolean',
        'allow_comment' => 'boolean',
        'view_count'   => 'integer',
    ];

    // Auto-generate slug
    protected static function booted(): void
    {
        static::creating(function ($berita) {
            if (empty($berita->slug)) {
                $berita->slug = Str::slug($berita->judul);
            }
        });
    }

    // Relations
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_id');
    }

    public function penulis(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(TagBerita::class, 'berita_tag', 'berita_id', 'tag_id');
    }

    public function komentar(): HasMany
    {
        return $this->hasMany(KomentarBerita::class);
    }

    public function komentarDisetujui(): HasMany
    {
        return $this->hasMany(KomentarBerita::class)->where('status', 'approved');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->whereNotNull('published_at')
                     ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    // Methods
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/' . $this->thumbnail);
        }
        return asset('images/default-news.jpg');
    }

    public function getExcerptAutoAttribute(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }
        return Str::limit(strip_tags($this->konten), 200);
    }
}
