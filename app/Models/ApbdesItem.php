<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbdesItem extends Model
{
    protected $table = 'apbdes_item';
    protected $guarded = ['id'];

    public function tahun()
    {
        return $this->belongsTo(ApbdesTahun::class, 'tahun_id');
    }
}
