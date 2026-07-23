<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ================================================
        // PERMISSIONS
        // ================================================
        $permissions = [
            // Users & Roles
            'user.view', 'user.create', 'user.edit', 'user.delete',
            'role.view', 'role.create', 'role.edit', 'role.delete',

            // Penduduk
            'penduduk.view', 'penduduk.create', 'penduduk.edit', 'penduduk.delete',
            'penduduk.import', 'penduduk.export', 'penduduk.print',
            'penduduk.view-own-wilayah',  // Ketua RT/RW: hanya lihat wilayah sendiri

            // Berita
            'berita.view', 'berita.create', 'berita.edit', 'berita.delete', 'berita.publish',

            // Galeri
            'galeri.view', 'galeri.create', 'galeri.edit', 'galeri.delete',

            // Agenda
            'agenda.view', 'agenda.create', 'agenda.edit', 'agenda.delete',

            // Pengumuman
            'pengumuman.view', 'pengumuman.create', 'pengumuman.edit', 'pengumuman.delete',

            // Pengaduan
            'pengaduan.view', 'pengaduan.create', 'pengaduan.edit', 'pengaduan.delete',
            'pengaduan.update-status', 'pengaduan.respond',

            // APBDes
            'apbdes.view', 'apbdes.create', 'apbdes.edit', 'apbdes.delete',

            // UMKM & Potensi
            'umkm.view', 'umkm.create', 'umkm.edit', 'umkm.delete',
            'potensi.view', 'potensi.create', 'potensi.edit', 'potensi.delete',

            // Perangkat Desa
            'perangkat.view', 'perangkat.create', 'perangkat.edit', 'perangkat.delete',

            // Laporan
            'laporan.view', 'laporan.export',

            // Setting
            'setting.view', 'setting.edit',

            // Audit
            'audit.view',

            // Backup
            'backup.view', 'backup.create', 'backup.download', 'backup.delete',

            // Dashboard
            'dashboard.view', 'dashboard.statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ================================================
        // ROLES & ASSIGN PERMISSIONS
        // ================================================

        // 1. Super Admin - semua akses
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // 2. Admin Desa
        $adminDesa = Role::firstOrCreate(['name' => 'admin-desa', 'guard_name' => 'web']);
        $adminDesa->givePermissionTo([
            'dashboard.view', 'dashboard.statistics',
            'user.view', 'user.create', 'user.edit',
            'penduduk.view', 'penduduk.create', 'penduduk.edit', 'penduduk.delete',
            'penduduk.import', 'penduduk.export', 'penduduk.print',
            'berita.view', 'berita.create', 'berita.edit', 'berita.delete', 'berita.publish',
            'galeri.view', 'galeri.create', 'galeri.edit', 'galeri.delete',
            'agenda.view', 'agenda.create', 'agenda.edit', 'agenda.delete',
            'pengumuman.view', 'pengumuman.create', 'pengumuman.edit', 'pengumuman.delete',
            'pengaduan.view', 'pengaduan.update-status', 'pengaduan.respond',
            'apbdes.view', 'apbdes.create', 'apbdes.edit', 'apbdes.delete',
            'umkm.view', 'umkm.create', 'umkm.edit', 'umkm.delete',
            'potensi.view', 'potensi.create', 'potensi.edit', 'potensi.delete',
            'perangkat.view', 'perangkat.create', 'perangkat.edit', 'perangkat.delete',
            'laporan.view', 'laporan.export',
            'setting.view', 'setting.edit',
            'audit.view',
        ]);

        // 3. Kepala Desa - view semua, export laporan
        $kepalaDesa = Role::firstOrCreate(['name' => 'kepala-desa', 'guard_name' => 'web']);
        $kepalaDesa->givePermissionTo([
            'dashboard.view', 'dashboard.statistics',
            'penduduk.view', 'penduduk.export', 'penduduk.print',
            'berita.view',
            'agenda.view',
            'pengumuman.view',
            'pengaduan.view',
            'apbdes.view',
            'umkm.view',
            'potensi.view',
            'perangkat.view',
            'laporan.view', 'laporan.export',
        ]);

        // 4. Sekretaris Desa
        $sekretaris = Role::firstOrCreate(['name' => 'sekretaris-desa', 'guard_name' => 'web']);
        $sekretaris->givePermissionTo([
            'dashboard.view', 'dashboard.statistics',
            'penduduk.view', 'penduduk.create', 'penduduk.edit',
            'penduduk.import', 'penduduk.export', 'penduduk.print',
            'berita.view', 'berita.create', 'berita.edit',
            'agenda.view', 'agenda.create', 'agenda.edit',
            'pengumuman.view', 'pengumuman.create', 'pengumuman.edit',
            'pengaduan.view', 'pengaduan.respond',
            'apbdes.view', 'apbdes.create', 'apbdes.edit',
            'laporan.view', 'laporan.export',
        ]);

        // 5. Kasi Pemerintahan
        $kasiPemerintahan = Role::firstOrCreate(['name' => 'kasi-pemerintahan', 'guard_name' => 'web']);
        $kasiPemerintahan->givePermissionTo([
            'dashboard.view',
            'penduduk.view', 'penduduk.create', 'penduduk.edit',
            'penduduk.import', 'penduduk.export',
            'pengaduan.view', 'pengaduan.update-status', 'pengaduan.respond',
            'apbdes.view', 'apbdes.create', 'apbdes.edit',
            'umkm.view', 'umkm.create', 'umkm.edit',
            'laporan.view', 'laporan.export',
        ]);

        // 6. Operator
        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operator->givePermissionTo([
            'dashboard.view',
            'penduduk.view', 'penduduk.create', 'penduduk.edit',
            'berita.view', 'berita.create', 'berita.edit',
            'galeri.view', 'galeri.create', 'galeri.edit',
            'agenda.view', 'agenda.create', 'agenda.edit',
            'umkm.view', 'umkm.create', 'umkm.edit',
        ]);

        // 7. Ketua RW - hanya lihat wilayah sendiri
        $ketuaRw = Role::firstOrCreate(['name' => 'ketua-rw', 'guard_name' => 'web']);
        $ketuaRw->givePermissionTo([
            'dashboard.view',
            'penduduk.view', 'penduduk.view-own-wilayah',
        ]);

        // 8. Ketua RT - hanya lihat wilayah sendiri
        $ketuaRt = Role::firstOrCreate(['name' => 'ketua-rt', 'guard_name' => 'web']);
        $ketuaRt->givePermissionTo([
            'dashboard.view',
            'penduduk.view', 'penduduk.view-own-wilayah',
        ]);

        // 9. Masyarakat - hanya buat pengaduan
        $masyarakat = Role::firstOrCreate(['name' => 'masyarakat', 'guard_name' => 'web']);
        $masyarakat->givePermissionTo([
            'pengaduan.create',
        ]);

        $this->command->info('✅ Roles dan Permissions berhasil dibuat!');
    }
}
