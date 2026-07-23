<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\ApbdesTahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatistikController extends Controller
{
    public function index()
    {
        $totalPenduduk = Penduduk::count();
        $lakiLaki = Penduduk::where('jenis_kelamin', 'L')->count();
        $perempuan = Penduduk::where('jenis_kelamin', 'P')->count();
        $totalKk = Penduduk::distinct('nomor_kk')->count('nomor_kk');

        $byAgama = Penduduk::select('agama_id', DB::raw('count(*) as total'))
            ->with('agama')
            ->groupBy('agama_id')
            ->get()
            ->map(function ($item) {
                return [
                    'agama' => $item->agama ? $item->agama->nama : 'Lainnya',
                    'total' => $item->total,
                ];
            });

        $byPendidikan = Penduduk::select('pendidikan_id', DB::raw('count(*) as total'))
            ->with('pendidikan')
            ->groupBy('pendidikan_id')
            ->get()
            ->map(function ($item) {
                return [
                    'pendidikan' => $item->pendidikan ? $item->pendidikan->nama : 'Lainnya',
                    'total' => $item->total,
                ];
            });

        $apbdesTerbaru = ApbdesTahun::with('items')->latest('tahun')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'demografi' => [
                    'total_penduduk' => $totalPenduduk,
                    'laki_laki' => $lakiLaki,
                    'perempuan' => $perempuan,
                    'total_kk' => $totalKk,
                    'by_agama' => $byAgama,
                    'by_pendidikan' => $byPendidikan,
                ],
                'apbdes' => $apbdesTerbaru,
            ]
        ]);
    }
}
