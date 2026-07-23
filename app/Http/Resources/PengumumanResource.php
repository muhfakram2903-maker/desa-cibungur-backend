<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengumumanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'slug' => $this->slug,
            'isi' => $this->isi,
            'file_lampiran_url' => $this->file_lampiran ? asset('storage/' . $this->file_lampiran) : null,
            'prioritas' => $this->prioritas,
            'expired_at' => $this->expired_at ? $this->expired_at->format('Y-m-d') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
