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
            'konten' => $this->konten,
            'file_lampiran_url' => $this->lampiran ? asset('storage/' . $this->lampiran) : null,
            'prioritas' => $this->prioritas,
            'tanggal_terbit' => $this->tanggal_terbit ? $this->tanggal_terbit->format('Y-m-d') : null,
            'tanggal_expired' => $this->tanggal_expired ? $this->tanggal_expired->format('Y-m-d') : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
