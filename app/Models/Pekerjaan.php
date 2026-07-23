<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pekerjaan extends Model
{
    protected $table = 'pekerjaan';
    protected $fillable = ['nama'];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }
}
