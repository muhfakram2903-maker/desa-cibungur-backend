<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'judul' => $this->judul,
            'slug' => $this->slug,
            'lokasi' => $this->lokasi,
            'tgl_mulai' => $this->tgl_mulai ? $this->tgl_mulai->format('Y-m-d') : null,
            'tgl_selesai' => $this->tgl_selesai ? $this->tgl_selesai->format('Y-m-d') : null,
            'jam' => $this->jam,
            'deskripsi' => $this->deskripsi,
            'poster_url' => $this->poster ? asset('storage/' . $this->poster) : null,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
