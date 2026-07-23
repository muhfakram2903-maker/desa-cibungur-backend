<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbdesTahun extends Model
{
    protected $table = 'apbdes_tahun';
    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(ApbdesItem::class, 'tahun_id');
    }
}
