<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UmkmResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_usaha' => $this->nama_usaha,
            'slug' => $this->slug,
            'pemilik' => $this->pemilik,
            'deskripsi' => $this->deskripsi,
            'alamat' => $this->alamat,
            'no_wa' => $this->no_wa,
            'instagram' => $this->instagram,
            'marketplace_url' => $this->marketplace_url,
            'foto_url' => $this->foto ? asset('storage/' . $this->foto) : null,
            'kategori' => $this->whenLoaded('kategori', function () {
                return [
                    'id' => $this->kategori->id,
                    'nama' => $this->kategori->nama,
                    'slug' => $this->kategori->slug,
                ];
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
