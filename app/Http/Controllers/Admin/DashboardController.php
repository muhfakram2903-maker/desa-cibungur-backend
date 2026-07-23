<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penduduk;
use App\Models\Berita;
use App\Models\Pengaduan;
use App\Models\Agenda;
use App\Models\Umkm;
use App\Models\GaleriItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Widget Statistik (cached 5 menit)
        $stats = Cache::remember('admin_dashboard_stats', 300, function () {
            return [
                'total_penduduk'   => Penduduk::aktif()->count(),
                'total_kk'         => Penduduk::aktif()->where('status_keluarga', 'kepala_keluarga')->count(),
                'laki_laki'        => Penduduk::aktif()->lakiLaki()->count(),
                'perempuan'        => Penduduk::aktif()->perempuan()->count(),
                'total_berita'     => Berita::published()->count(),
                'total_pengaduan'  => Pengaduan::count(),
                'pengaduan_baru'   => Pengaduan::byStatus('menunggu')->count(),
                'total_agenda'     => Agenda::where('status', 'published')->count(),
                'total_umkm'       => Umkm::where('status', 'active')->count(),
                'total_user'       => User::active()->count(),
                'total_foto'       => GaleriItem::where('tipe', 'foto')->count(),
            ];
        });

        // Pengaduan per status untuk chart
        $pengaduanChart = Cache::remember('pengaduan_chart', 600, function () {
            return [
                'menunggu' => Pengaduan::byStatus('menunggu')->count(),
                'diproses' => Pengaduan::byStatus('diproses')->count(),
                'selesai'  => Pengaduan::byStatus('selesai')->count(),
                'ditolak'  => Pengaduan::byStatus('ditolak')->count(),
            ];
        });

        // Pengaduan per bulan (12 bulan terakhir)
        $pengaduanBulanan = Cache::remember('pengaduan_bulanan', 600, function () {
            $data = Pengaduan::select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('YEAR(created_at) as tahun'),
                DB::raw('count(*) as total')
            )
            ->whereDate('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun')
            ->orderBy('bulan')
            ->get();

            return $data;
        });

        // Aktivitas terbaru
        $beritaTerbaru    = Berita::published()->latest()->limit(5)->get(['id', 'judul', 'slug', 'published_at']);
        $pengaduanTerbaru = Pengaduan::with(['kategori', 'user'])->latest()->limit(5)->get();
        $agendaAktif      = Agenda::where('status', 'published')
            ->where('tanggal_mulai', '>=', today())
            ->orderBy('tanggal_mulai')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'pengaduanChart',
            'pengaduanBulanan',
            'beritaTerbaru',
            'pengaduanTerbaru',
            'agendaAktif'
        ));
    }
}
