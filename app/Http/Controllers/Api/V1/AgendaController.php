<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgendaResource;
use App\Models\Agenda;
use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $agenda = Agenda::latest('tgl_mulai')->paginate($request->get('per_page', 10));

        return AgendaResource::collection($agenda);
    }
}
