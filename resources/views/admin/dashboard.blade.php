@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')

<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="text-muted mb-0" style="font-size:0.85rem">
            Selamat datang, <strong>{{ auth()->user()->name }}</strong>!
            Data terakhir diperbarui: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.penduduk.index') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-people me-1"></i> Data Penduduk
        </a>
        <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-chat-dots me-1"></i> Pengaduan
            @if($stats['pengaduan_baru'] > 0)
                <span class="badge bg-danger">{{ $stats['pengaduan_baru'] }}</span>
            @endif
        </a>
    </div>
</div>

<!-- STATISTIK CARDS ROW 1 -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-green">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_penduduk']) }}</div>
                    <div class="stat-label mt-1">Total Penduduk</div>
                </div>
                <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            </div>
            <div class="mt-2" style="font-size:0.75rem;opacity:.8">
                ♂ {{ number_format($stats['laki_laki']) }} &nbsp; ♀ {{ number_format($stats['perempuan']) }}
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-blue">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_kk']) }}</div>
                    <div class="stat-label mt-1">Jumlah KK</div>
                </div>
                <div class="stat-icon"><i class="bi bi-house-fill"></i></div>
            </div>
            <div class="mt-2" style="font-size:0.75rem;opacity:.8">
                Kepala Keluarga Aktif
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-orange">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_pengaduan']) }}</div>
                    <div class="stat-label mt-1">Total Pengaduan</div>
                </div>
                <div class="stat-icon"><i class="bi bi-chat-dots-fill"></i></div>
            </div>
            <div class="mt-2" style="font-size:0.75rem;opacity:.8">
                <span class="badge bg-warning text-dark">{{ $stats['pengaduan_baru'] }} Menunggu</span>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-purple">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_berita']) }}</div>
                    <div class="stat-label mt-1">Berita Terbit</div>
                </div>
                <div class="stat-icon"><i class="bi bi-newspaper"></i></div>
            </div>
            <div class="mt-2" style="font-size:0.75rem;opacity:.8">
                {{ $stats['total_agenda'] }} Agenda Aktif
            </div>
        </div>
    </div>
</div>

<!-- STATISTIK CARDS ROW 2 -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-teal">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_umkm']) }}</div>
                    <div class="stat-label mt-1">UMKM Terdaftar</div>
                </div>
                <div class="stat-icon"><i class="bi bi-shop"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-gold">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_foto']) }}</div>
                    <div class="stat-label mt-1" style="color:#333">Foto di Galeri</div>
                </div>
                <div class="stat-icon" style="color:#333"><i class="bi bi-images"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card bg-grad-dark">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($stats['total_user']) }}</div>
                    <div class="stat-label mt-1">Total User Aktif</div>
                </div>
                <div class="stat-icon"><i class="bi bi-person-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card" style="background: linear-gradient(135deg, #AD1457, #EC407A)">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">
                        {{ $stats['laki_laki'] > 0 ? round(($stats['perempuan'] / $stats['total_penduduk']) * 100, 1) : 0 }}%
                    </div>
                    <div class="stat-label mt-1">Rasio Perempuan</div>
                </div>
                <div class="stat-icon"><i class="bi bi-gender-female"></i></div>
            </div>
            <div class="mt-2" style="font-size:0.75rem;opacity:.8">
                dari total {{ number_format($stats['total_penduduk']) }} penduduk
            </div>
        </div>
    </div>
</div>

<!-- CHARTS ROW -->
<div class="row g-3 mb-4">
    <!-- Chart Pengaduan Bulanan -->
    <div class="col-xl-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">📊 Grafik Pengaduan (12 Bulan Terakhir)</h6>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
                <canvas id="chartPengaduanBulanan" height="80"></canvas>
            </div>
        </div>
    </div>

    <!-- Donut Chart Status Pengaduan -->
    <div class="col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3 fw-bold">🔄 Status Pengaduan</h6>
                <canvas id="chartStatusPengaduan" height="200"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-warning fw-medium">● Menunggu</small>
                        <small class="fw-bold">{{ $pengaduanChart['menunggu'] }}</small>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-info fw-medium">● Diproses</small>
                        <small class="fw-bold">{{ $pengaduanChart['diproses'] }}</small>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <small class="text-success fw-medium">● Selesai</small>
                        <small class="fw-bold">{{ $pengaduanChart['selesai'] }}</small>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-danger fw-medium">● Ditolak</small>
                        <small class="fw-bold">{{ $pengaduanChart['ditolak'] }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TABEL AKTIVITAS TERBARU -->
<div class="row g-3">
    <!-- Berita Terbaru -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">📰 Berita Terbaru</h6>
                    <a href="{{ route('admin.berita.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus"></i> Tambah
                    </a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($beritaTerbaru as $berita)
                    <div class="list-group-item px-0 d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <a href="{{ route('admin.berita.edit', $berita->id) }}"
                               class="text-decoration-none text-dark fw-medium"
                               style="font-size:0.85rem">
                                {{ Str::limit($berita->judul, 60) }}
                            </a>
                            <div style="font-size:0.75rem;color:#9ca3af">
                                {{ $berita->published_at?->diffForHumans() }}
                                · {{ number_format($berita->view_count) }} views
                            </div>
                        </div>
                        <span class="badge bg-success">Terbit</span>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3" style="font-size:0.85rem">Belum ada berita</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Pengaduan Terbaru -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 fw-bold">📢 Pengaduan Terbaru</h6>
                    <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-outline-warning btn-sm">
                        Semua Pengaduan
                    </a>
                </div>
                <div class="list-group list-group-flush">
                    @forelse($pengaduanTerbaru as $pengaduan)
                    <div class="list-group-item px-0 d-flex align-items-center gap-2">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <a href="{{ route('admin.pengaduan.show', $pengaduan->id) }}"
                                   class="text-decoration-none text-dark fw-medium"
                                   style="font-size:0.85rem">
                                    {{ Str::limit($pengaduan->judul, 50) }}
                                </a>
                                @php
                                    $statusColors = ['menunggu'=>'warning','diproses'=>'info','selesai'=>'success','ditolak'=>'danger'];
                                    $statusLabels = ['menunggu'=>'Menunggu','diproses'=>'Diproses','selesai'=>'Selesai','ditolak'=>'Ditolak'];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$pengaduan->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$pengaduan->status] ?? $pengaduan->status }}
                                </span>
                            </div>
                            <div style="font-size:0.75rem;color:#9ca3af">
                                {{ $pengaduan->nomor_tiket }} · {{ $pengaduan->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted text-center py-3" style="font-size:0.85rem">Belum ada pengaduan</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Chart Pengaduan Bulanan
const ctxBulanan = document.getElementById('chartPengaduanBulanan').getContext('2d');
new Chart(ctxBulanan, {
    type: 'bar',
    data: {
        labels: {!! json_encode($pengaduanBulanan->map(fn($d) => \Carbon\Carbon::createFromDate($d->tahun, $d->bulan, 1)->translatedFormat('M Y'))) !!},
        datasets: [{
            label: 'Jumlah Pengaduan',
            data: {!! json_encode($pengaduanBulanan->pluck('total')) !!},
            backgroundColor: 'rgba(46, 125, 50, 0.8)',
            borderColor: '#2E7D32',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
            x: { grid: { display: false } }
        }
    }
});

// Chart Status Pengaduan (Donut)
const ctxStatus = document.getElementById('chartStatusPengaduan').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Menunggu', 'Diproses', 'Selesai', 'Ditolak'],
        datasets: [{
            data: [
                {{ $pengaduanChart['menunggu'] }},
                {{ $pengaduanChart['diproses'] }},
                {{ $pengaduanChart['selesai'] }},
                {{ $pengaduanChart['ditolak'] }},
            ],
            backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#EF4444'],
            borderWidth: 0,
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: { display: false }
        }
    }
});
</script>
@endpush
