<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengaduanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nomor_tiket' => $this->nomor_tiket,
            'judul' => $this->judul,
            'lokasi' => $this->lokasi,
            'deskripsi' => $this->deskripsi,
            'foto_url' => $this->foto ? asset('storage/' . $this->foto) : null,
            'video_url' => $this->video ? asset('storage/' . $this->video) : null,
            'status' => $this->status,
            'tgl_pengaduan' => $this->tgl_pengaduan ? $this->tgl_pengaduan->format('Y-m-d H:i:s') : null,
            'kategori' => $this->whenLoaded('kategori', function () {
                return [
                    'id' => $this->kategori->id,
                    'nama' => $this->kategori->nama,
                ];
            }),
            'respon' => $this->whenLoaded('respon', function () {
                return $this->respon->map(function ($res) {
                    return [
                        'id' => $res->id,
                        'pesan' => $res->pesan,
                        'foto_lampiran_url' => $res->foto_lampiran ? asset('storage/' . $res->foto_lampiran) : null,
                        'created_at' => $res->created_at ? $res->created_at->format('Y-m-d H:i:s') : null,
                        'user' => [
                            'name' => $res->user ? $res->user->name : 'Admin',
                        ]
                    ];
                });
            }),
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
