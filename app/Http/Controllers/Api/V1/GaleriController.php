<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\GaleriResource;
use App\Models\GaleriAlbum;
use Illuminate\Http\Request;

class GaleriController extends Controller
{
    public function index(Request $request)
    {
        $album = GaleriAlbum::with('items')->latest()->paginate($request->get('per_page', 10));

        return GaleriResource::collection($album);
    }

    public function show($slug)
    {
        $album = GaleriAlbum::with('items')->where('slug', $slug)->firstOrFail();

        return new GaleriResource($album);
    }
}
