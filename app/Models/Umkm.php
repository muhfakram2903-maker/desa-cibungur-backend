<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Umkm extends Model
{
    protected $table = 'umkm';
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(PotensiKategori::class, 'kategori_id');
    }
}
