<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agama extends Model
{
    protected $table = 'agama';
    protected $fillable = ['nama'];

    public function penduduk(): HasMany
    {
        return $this->hasMany(Penduduk::class);
    }
}
