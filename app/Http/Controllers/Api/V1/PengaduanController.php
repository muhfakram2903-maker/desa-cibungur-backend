<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengaduanResource;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PengaduanController extends Controller
{
    public function index(Request $request)
    {
        $pengaduan = Pengaduan::with(['kategori', 'respon.user'])
            ->where('user_id', $request->user()->id)
            ->latest('tgl_pengaduan')
            ->paginate($request->get('per_page', 10));

        return PengaduanResource::collection($pengaduan);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori_pengaduan,id',
            'lokasi' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|max:5120',
            'video' => 'nullable|mimes:mp4,mov,avi|max:20480',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('pengaduan/foto', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('pengaduan/video', 'public');
        }

        $nomorTiket = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(5));

        $pengaduan = Pengaduan::create([
            'nomor_tiket' => $nomorTiket,
            'user_id' => $request->user() ? $request->user()->id : null,
            'kategori_id' => $request->kategori_id,
            'judul' => $request->judul,
            'lokasi' => $request->lokasi,
            'deskripsi' => $request->deskripsi,
            'foto' => $fotoPath,
            'video' => $videoPath,
            'status' => 'menunggu',
            'tgl_pengaduan' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengaduan berhasil dikirim',
            'data' => new PengaduanResource($pengaduan->load('kategori'))
        ], 201);
    }

    public function show($id)
    {
        $pengaduan = Pengaduan::with(['kategori', 'respon.user'])
            ->findOrFail($id);

        return new PengaduanResource($pengaduan);
    }

    public function track($tiket)
    {
        $pengaduan = Pengaduan::with(['kategori', 'respon.user'])
            ->where('nomor_tiket', $tiket)
            ->firstOrFail();

        return new PengaduanResource($pengaduan);
    }
}
