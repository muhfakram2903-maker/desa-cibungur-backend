<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Agenda;
use App\Models\Pengumuman;
use App\Models\Slider;
use App\Models\Umkm;
use App\Models\GaleriItem;
use App\Models\PotensiDesa;
use App\Models\Setting;
use App\Services\PendudukService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function __construct(
        private readonly PendudukService $pendudukService
    ) {}

    public function index()
    {
        // Sliders untuk hero section
        $sliders = Cache::remember('home_sliders', 3600, function () {
            return Slider::where('is_active', true)->orderBy('urutan')->get();
        });

        // Berita terbaru (6 berita)
        $beritaTerbaru = Cache::remember('home_berita', 600, function () {
            return Berita::published()
                ->with(['kategori', 'penulis'])
                ->latest('published_at')
                ->limit(6)
                ->get(['id', 'judul', 'slug', 'excerpt', 'thumbnail', 'published_at', 'view_count', 'kategori_id', 'user_id']);
        });

        // Agenda mendatang
        $agendaMendatang = Cache::remember('home_agenda', 600, function () {
            return Agenda::where('status', 'published')
                ->where('tanggal_mulai', '>=', today())
                ->orderBy('tanggal_mulai')
                ->limit(4)
                ->get();
        });

        // Pengumuman aktif terbaru
        $pengumuman = Cache::remember('home_pengumuman', 1800, function () {
            return Pengumuman::where('status', 'published')
                ->where(function ($q) {
                    $q->whereNull('tanggal_expired')
                      ->orWhere('tanggal_expired', '>=', today());
                })
                ->orderBy('prioritas', 'desc')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        });

        // UMKM Unggulan
        $umkmUnggulan = Cache::remember('home_umkm', 3600, function () {
            return Umkm::where('status', 'active')
                ->latest()
                ->limit(6)
                ->get();
        });

        // Galeri terbaru
        $galeriTerbaru = Cache::remember('home_galeri', 3600, function () {
            return GaleriItem::with('album')
                ->where('tipe', 'foto')
                ->latest()
                ->limit(8)
                ->get();
        });

        // Potensi Desa
        $potensiDesa = Cache::remember('home_potensi', 3600, function () {
            return PotensiDesa::with('kategori')
                ->where('status', 'active')
                ->limit(4)
                ->get();
        });

        // Statistik Penduduk untuk widget
        $statistikPenduduk = Cache::remember('home_statistik', 3600, function () {
            return $this->pendudukService->getStatistik();
        });

        // Settings desa untuk peta, kontak, dll
        $settings = Cache::remember('home_settings', 86400, function () {
            return Setting::where('is_public', true)
                ->pluck('value', 'key')
                ->toArray();
        });

        return view('public.home', compact(
            'sliders',
            'beritaTerbaru',
            'agendaMendatang',
            'pengumuman',
            'umkmUnggulan',
            'galeriTerbaru',
            'potensiDesa',
            'statistikPenduduk',
            'settings'
        ));
    }
}
