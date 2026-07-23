<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengumumanResource;
use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request)
    {
        $pengumuman = Pengumuman::where(function ($q) {
            $q->whereNull('tanggal_expired')->orWhere('tanggal_expired', '>=', now());
        })
        ->where('status', 'published')
        ->latest('created_at')
        ->paginate($request->get('per_page', 10));

        return PengumumanResource::collection($pengumuman);
    }
}
