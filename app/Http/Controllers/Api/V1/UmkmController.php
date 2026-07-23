<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UmkmResource;
use App\Models\Umkm;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $query = Umkm::with('kategori');

        if ($request->has('search')) {
            $query->where('nama_usaha', 'like', '%' . $request->get('search') . '%');
        }

        $umkm = $query->latest()->paginate($request->get('per_page', 10));

        return UmkmResource::collection($umkm);
    }

    public function show($slug)
    {
        $umkm = Umkm::with('kategori')->where('slug', $slug)->firstOrFail();

        return new UmkmResource($umkm);
    }
}
