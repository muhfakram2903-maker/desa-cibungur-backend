<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Dashboard Admin Website Resmi Desa Cibungur')">

    <title>@yield('title', 'Dashboard') | Admin Desa Cibungur</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-green: #2E7D32;
            --primary-green-light: #4CAF50;
            --primary-green-dark: #1B5E20;
            --primary-gold: #D4AF37;
            --primary-gold-light: #F0D060;
            --sidebar-bg: #1a2332;
            --sidebar-hover: #2d3f55;
            --card-shadow: 0 2px 12px rgba(0,0,0,.08);
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background: #f0f2f5;
            color: #333;
        }

        /* ---- SIDEBAR ---- */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0; top: 0;
            z-index: 1000;
            transition: all .3s ease;
            overflow-y: auto;
            overflow-x: hidden;
        }

        #sidebar.collapsed { width: 70px; }
        #sidebar.collapsed .sidebar-label { display: none; }
        #sidebar.collapsed .sidebar-brand-text { display: none; }

        .sidebar-brand {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sidebar-brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-gold));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .sidebar-brand-text h6 {
            color: white;
            font-size: 0.85rem;
            font-weight: 600;
            margin: 0;
        }
        .sidebar-brand-text small {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
        }

        .sidebar-nav { padding: 0.5rem 0; }

        .sidebar-section {
            padding: 0.75rem 1rem 0.3rem;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: rgba(255,255,255,0.3);
            font-weight: 600;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 1rem;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all .2s;
            border-radius: 0;
            margin: 0.1rem 0.5rem;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .sidebar-item:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .sidebar-item.active {
            background: var(--primary-green);
            color: white;
        }

        .sidebar-item .si-icon {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
        }

        .sidebar-badge {
            margin-left: auto;
            background: var(--primary-gold);
            color: #000;
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 10px;
            font-weight: 700;
        }

        /* ---- MAIN CONTENT ---- */
        #main-content {
            margin-left: 260px;
            transition: all .3s ease;
            min-height: 100vh;
        }

        #main-content.expanded { margin-left: 70px; }

        /* ---- TOPBAR ---- */
        .topbar {
            background: white;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }

        .topbar-left { display: flex; align-items: center; gap: 1rem; }

        .btn-sidebar-toggle {
            border: none;
            background: #f3f4f6;
            padding: 0.5rem 0.6rem;
            border-radius: 8px;
            color: #555;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-sidebar-toggle:hover { background: #e5e7eb; }

        .page-breadcrumb { font-size: 0.8rem; color: #888; }
        .page-breadcrumb a { color: var(--primary-green); text-decoration: none; }

        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }

        .avatar-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-green), var(--primary-green-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* ---- CARDS ---- */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
        }

        .stat-card {
            border-radius: 12px;
            padding: 1.25rem;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            right: -20px;
            top: -20px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: rgba(255,255,255,0.1);
        }

        .stat-card .stat-icon {
            font-size: 2rem;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            line-height: 1;
        }

        .stat-card .stat-label {
            font-size: 0.8rem;
            opacity: 0.85;
        }

        .bg-grad-green  { background: linear-gradient(135deg, #2E7D32, #4CAF50); }
        .bg-grad-blue   { background: linear-gradient(135deg, #1565C0, #42A5F5); }
        .bg-grad-orange { background: linear-gradient(135deg, #E65100, #FF7043); }
        .bg-grad-purple { background: linear-gradient(135deg, #4527A0, #7E57C2); }
        .bg-grad-teal   { background: linear-gradient(135deg, #00695C, #26A69A); }
        .bg-grad-red    { background: linear-gradient(135deg, #B71C1C, #EF5350); }
        .bg-grad-gold   { background: linear-gradient(135deg, #D4AF37, #F0D060); }
        .bg-grad-dark   { background: linear-gradient(135deg, #1a2332, #2d3f55); }

        /* ---- CONTENT AREA ---- */
        .content-area { padding: 1.5rem; }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1a2332;
            margin: 0;
        }

        /* ---- TABLES ---- */
        .table { font-size: 0.875rem; }
        .table th {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            background: #f9fafb;
        }

        /* ---- BADGES ---- */
        .badge { font-size: 0.7rem; font-weight: 500; }

        /* ---- FORMS ---- */
        .form-label { font-size: 0.875rem; font-weight: 500; color: #374151; }
        .form-control, .form-select {
            font-size: 0.875rem;
            border-color: #d1d5db;
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
        }

        /* ---- BUTTONS ---- */
        .btn-primary {
            background: var(--primary-green);
            border-color: var(--primary-green);
        }
        .btn-primary:hover { background: var(--primary-green-dark); }

        .btn-sm { font-size: 0.775rem; padding: 0.3rem 0.6rem; border-radius: 6px; }

        /* ---- ALERTS ---- */
        .alert { border-radius: 10px; border: none; font-size: 0.875rem; }

        /* ---- NOTIFICATION DOT ---- */
        .notif-dot {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 8px;
            height: 8px;
            background: #EF5350;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* ---- FOOTER ---- */
        .admin-footer {
            background: white;
            padding: 0.75rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            font-size: 0.8rem;
            color: #9ca3af;
            text-align: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- SIDEBAR -->
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">🏛️</div>
        <div class="sidebar-brand-text">
            <h6>Desa Cibungur</h6>
            <small>Panel Admin</small>
        </div>
    </div>

    <nav class="sidebar-nav">

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 si-icon"></i>
            <span class="sidebar-label">Dashboard</span>
        </a>

        <!-- Data Desa -->
        <div class="sidebar-section sidebar-label">DATA DESA</div>

        <a href="{{ route('admin.penduduk.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.penduduk*') ? 'active' : '' }}">
            <i class="bi bi-people si-icon"></i>
            <span class="sidebar-label">Data Penduduk</span>
        </a>

        <a href="{{ route('admin.perangkat-desa.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.perangkat-desa*') ? 'active' : '' }}">
            <i class="bi bi-person-badge si-icon"></i>
            <span class="sidebar-label">Perangkat Desa</span>
        </a>

        <a href="{{ route('admin.apbdes.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.apbdes*') ? 'active' : '' }}">
            <i class="bi bi-graph-up si-icon"></i>
            <span class="sidebar-label">APBDes</span>
        </a>

        <!-- Konten -->
        <div class="sidebar-section sidebar-label">KONTEN WEBSITE</div>

        <a href="{{ route('admin.berita.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.berita*') ? 'active' : '' }}">
            <i class="bi bi-newspaper si-icon"></i>
            <span class="sidebar-label">Berita</span>
        </a>

        <a href="{{ route('admin.galeri.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.galeri*') ? 'active' : '' }}">
            <i class="bi bi-images si-icon"></i>
            <span class="sidebar-label">Galeri</span>
        </a>

        <a href="{{ route('admin.agenda.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.agenda*') ? 'active' : '' }}">
            <i class="bi bi-calendar-event si-icon"></i>
            <span class="sidebar-label">Agenda</span>
        </a>

        <a href="{{ route('admin.pengumuman.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.pengumuman*') ? 'active' : '' }}">
            <i class="bi bi-megaphone si-icon"></i>
            <span class="sidebar-label">Pengumuman</span>
        </a>

        <!-- Potensi -->
        <div class="sidebar-section sidebar-label">POTENSI DESA</div>

        <a href="{{ route('admin.umkm.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.umkm*') ? 'active' : '' }}">
            <i class="bi bi-shop si-icon"></i>
            <span class="sidebar-label">UMKM</span>
        </a>

        <a href="{{ route('admin.potensi.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.potensi*') ? 'active' : '' }}">
            <i class="bi bi-tree si-icon"></i>
            <span class="sidebar-label">Potensi Desa</span>
        </a>

        <!-- Pelayanan -->
        <div class="sidebar-section sidebar-label">PELAYANAN</div>

        <a href="{{ route('admin.pengaduan.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.pengaduan*') ? 'active' : '' }}">
            <i class="bi bi-chat-dots si-icon"></i>
            <span class="sidebar-label">Pengaduan</span>
            @php $pengaduanBaru = \App\Models\Pengaduan::byStatus('menunggu')->count(); @endphp
            @if($pengaduanBaru > 0)
                <span class="sidebar-badge">{{ $pengaduanBaru }}</span>
            @endif
        </a>

        <a href="{{ route('admin.kontak-masuk.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.kontak-masuk*') ? 'active' : '' }}">
            <i class="bi bi-envelope si-icon"></i>
            <span class="sidebar-label">Kontak Masuk</span>
        </a>

        <a href="{{ route('admin.laporan.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-bar-graph si-icon"></i>
            <span class="sidebar-label">Laporan</span>
        </a>

        <!-- Sistem -->
        <div class="sidebar-section sidebar-label">SISTEM</div>

        @can('user.view')
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-person-gear si-icon"></i>
            <span class="sidebar-label">Manajemen User</span>
        </a>
        @endcan

        @can('role.view')
        <a href="{{ route('admin.roles.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.roles*') ? 'active' : '' }}">
            <i class="bi bi-shield-check si-icon"></i>
            <span class="sidebar-label">Role & Permission</span>
        </a>
        @endcan

        <a href="{{ route('admin.sliders.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.sliders*') ? 'active' : '' }}">
            <i class="bi bi-collection-play si-icon"></i>
            <span class="sidebar-label">Slider / Banner</span>
        </a>

        <a href="{{ route('admin.faqs.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
            <i class="bi bi-question-circle si-icon"></i>
            <span class="sidebar-label">FAQ</span>
        </a>

        @can('setting.view')
        <a href="{{ route('admin.settings.index') }}"
           class="sidebar-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
            <i class="bi bi-gear si-icon"></i>
            <span class="sidebar-label">Pengaturan</span>
        </a>
        @endcan

        <!-- Link ke Website Publik -->
        <div class="sidebar-section sidebar-label"></div>
        <a href="{{ route('home') }}" target="_blank" class="sidebar-item">
            <i class="bi bi-box-arrow-up-right si-icon"></i>
            <span class="sidebar-label">Lihat Website</span>
        </a>
    </nav>
</div>
<!-- END SIDEBAR -->

<!-- MAIN CONTENT -->
<div id="main-content">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn-sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                <i class="bi bi-list" style="font-size:1.2rem"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <!-- Notifications -->
            <div class="position-relative">
                <button class="btn-sidebar-toggle" title="Notifikasi">
                    <i class="bi bi-bell" style="font-size:1.1rem"></i>
                </button>
                <span class="notif-dot"></span>
            </div>

            <!-- User Dropdown -->
            <div class="dropdown">
                <button class="btn btn-link text-decoration-none d-flex align-items-center gap-2 p-0"
                        data-bs-toggle="dropdown">
                    <div class="avatar-circle">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div style="font-size:0.8rem;font-weight:600;color:#374151">{{ auth()->user()->name }}</div>
                        <div style="font-size:0.7rem;color:#9ca3af">{{ auth()->user()->getRoleNames()->first() }}</div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end mt-1" style="min-width:180px;border-radius:10px">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2"></i>Profil Saya
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END TOPBAR -->

    <!-- PAGE CONTENT -->
    <div class="content-area">

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
        @endif

        @yield('content')
    </div>
    <!-- END PAGE CONTENT -->

    <!-- FOOTER -->
    <div class="admin-footer">
        &copy; {{ date('Y') }} <strong>Website Resmi Desa Cibungur</strong> — Kecamatan Parungponteng, Kabupaten Tasikmalaya.
        Dikembangkan dengan ❤️ oleh Tim IT Desa.
    </div>
</div>
<!-- END MAIN CONTENT -->

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    // Sidebar Toggle
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const sidebarToggle = document.getElementById('sidebarToggle');

    sidebarToggle?.addEventListener('click', () => {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
    });

    // SweetAlert delete confirmation
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form') || document.getElementById(this.dataset.form);
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                borderRadius: '12px',
            }).then((result) => {
                if (result.isConfirmed && form) {
                    form.submit();
                }
            });
        });
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>

@stack('scripts')

</body>
</html>
