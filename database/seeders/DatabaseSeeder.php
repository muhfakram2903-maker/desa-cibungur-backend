<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Dusun;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\Agama;
use App\Models\Pendidikan;
use App\Models\Pekerjaan;
use App\Models\KategoriBerita;
use App\Models\KategoriPengaduan;
use App\Models\PotensiKategori;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\PerangkatDesa;
use App\Models\ApbdesTahun;
use App\Models\ApbdesItem;
use App\Models\Faq;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
        ]);

        $this->seedUsers();
        $this->seedWilayah();
        $this->seedReferensi();
        $this->seedKonten();
        $this->seedSettings();
        $this->seedPerangkatDesa();
        $this->seedApbdes();
        $this->seedFaq();

        $this->command->info('✅ Database seeding selesai!');
    }

    private function seedUsers(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@desa-cibungur.id'],
            [
                'name'              => 'Super Administrator',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super-admin');

        // Admin Desa
        $admin = User::firstOrCreate(
            ['email' => 'admin@desa-cibungur.id'],
            [
                'name'              => 'Admin Desa Cibungur',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin-desa');

        // Kepala Desa
        $kades = User::firstOrCreate(
            ['email' => 'kades@desa-cibungur.id'],
            [
                'name'              => 'Kepala Desa Cibungur',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $kades->assignRole('kepala-desa');

        // Operator
        $operator = User::firstOrCreate(
            ['email' => 'operator@desa-cibungur.id'],
            [
                'name'              => 'Operator Desa',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $operator->assignRole('operator');

        $this->command->info('  → Users created');
    }

    private function seedWilayah(): void
    {
        // Dusun
        $dusunData = [
            ['nama' => 'Dusun Cibungur', 'kode' => 'DSN-01'],
            ['nama' => 'Dusun Cicangkring', 'kode' => 'DSN-02'],
            ['nama' => 'Dusun Cihaur', 'kode' => 'DSN-03'],
        ];

        foreach ($dusunData as $data) {
            $dusun = Dusun::firstOrCreate(['nama' => $data['nama']], $data);

            // Buat 3 RW per Dusun
            for ($rwNum = 1; $rwNum <= 3; $rwNum++) {
                $rw = Rw::firstOrCreate(
                    ['nomor' => str_pad($rwNum, 2, '0', STR_PAD_LEFT), 'dusun_id' => $dusun->id]
                );

                // Buat 3 RT per RW
                for ($rtNum = 1; $rtNum <= 3; $rtNum++) {
                    Rt::firstOrCreate(
                        ['nomor' => str_pad($rtNum, 2, '0', STR_PAD_LEFT), 'rw_id' => $rw->id]
                    );
                }
            }
        }

        $this->command->info('  → Wilayah (Dusun/RW/RT) created');
    }

    private function seedReferensi(): void
    {
        // Agama
        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
        foreach ($agamaList as $agama) {
            Agama::firstOrCreate(['nama' => $agama]);
        }

        // Pendidikan
        $pendidikanList = [
            ['nama' => 'Tidak Tamat SD', 'urutan' => 1],
            ['nama' => 'SD/Sederajat', 'urutan' => 2],
            ['nama' => 'SMP/Sederajat', 'urutan' => 3],
            ['nama' => 'SMA/SMK/Sederajat', 'urutan' => 4],
            ['nama' => 'D1/D2/D3', 'urutan' => 5],
            ['nama' => 'S1/D4', 'urutan' => 6],
            ['nama' => 'S2', 'urutan' => 7],
            ['nama' => 'S3', 'urutan' => 8],
        ];
        foreach ($pendidikanList as $data) {
            Pendidikan::firstOrCreate(['nama' => $data['nama']], $data);
        }

        // Pekerjaan
        $pekerjaanList = [
            'Petani', 'Pedagang', 'PNS', 'TNI/POLRI', 'Swasta',
            'Wirausaha', 'Buruh', 'Nelayan', 'Ibu Rumah Tangga',
            'Pelajar/Mahasiswa', 'Tidak Bekerja', 'Pensiunan', 'Lainnya',
        ];
        foreach ($pekerjaanList as $pekerjaan) {
            Pekerjaan::firstOrCreate(['nama' => $pekerjaan]);
        }

        // Kategori Berita
        $kategoriBerita = [
            ['nama' => 'Pemerintahan', 'slug' => 'pemerintahan', 'warna' => '#2E7D32'],
            ['nama' => 'Pembangunan', 'slug' => 'pembangunan', 'warna' => '#1565C0'],
            ['nama' => 'Sosial Budaya', 'slug' => 'sosial-budaya', 'warna' => '#E65100'],
            ['nama' => 'Kesehatan', 'slug' => 'kesehatan', 'warna' => '#AD1457'],
            ['nama' => 'Pendidikan', 'slug' => 'pendidikan', 'warna' => '#4527A0'],
            ['nama' => 'Ekonomi', 'slug' => 'ekonomi', 'warna' => '#D4AF37'],
            ['nama' => 'Pengumuman', 'slug' => 'pengumuman-desa', 'warna' => '#37474F'],
        ];
        foreach ($kategoriBerita as $data) {
            KategoriBerita::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // Kategori Pengaduan
        $kategoriPengaduan = [
            ['nama' => 'Jalan Rusak', 'slug' => 'jalan-rusak', 'icon' => 'road'],
            ['nama' => 'Lampu Jalan', 'slug' => 'lampu-jalan', 'icon' => 'lightbulb'],
            ['nama' => 'Sampah', 'slug' => 'sampah', 'icon' => 'trash'],
            ['nama' => 'Banjir', 'slug' => 'banjir', 'icon' => 'water'],
            ['nama' => 'Pelayanan', 'slug' => 'pelayanan', 'icon' => 'building-office'],
            ['nama' => 'Keamanan', 'slug' => 'keamanan', 'icon' => 'shield-check'],
            ['nama' => 'Infrastruktur', 'slug' => 'infrastruktur', 'icon' => 'wrench-screwdriver'],
            ['nama' => 'Air Bersih', 'slug' => 'air-bersih', 'icon' => 'beaker'],
            ['nama' => 'Lainnya', 'slug' => 'lainnya', 'icon' => 'ellipsis-horizontal'],
        ];
        foreach ($kategoriPengaduan as $data) {
            KategoriPengaduan::firstOrCreate(['slug' => $data['slug']], $data);
        }

        // Kategori Potensi Desa
        $potensiKategori = [
            ['nama' => 'Pertanian', 'slug' => 'pertanian', 'icon' => '🌾'],
            ['nama' => 'Perikanan', 'slug' => 'perikanan', 'icon' => '🐟'],
            ['nama' => 'UMKM', 'slug' => 'umkm', 'icon' => '🏪'],
            ['nama' => 'Peternakan', 'slug' => 'peternakan', 'icon' => '🐄'],
            ['nama' => 'Wisata', 'slug' => 'wisata', 'icon' => '🏞️'],
            ['nama' => 'Budaya', 'slug' => 'budaya', 'icon' => '🎭'],
            ['nama' => 'Kerajinan', 'slug' => 'kerajinan', 'icon' => '🎨'],
        ];
        foreach ($potensiKategori as $data) {
            PotensiKategori::firstOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('  → Referensi data created');
    }

    private function seedSettings(): void
    {
        $settings = [
            // General
            ['key' => 'desa_nama', 'value' => 'Desa Cibungur', 'group' => 'general', 'tipe' => 'text', 'label' => 'Nama Desa'],
            ['key' => 'desa_kecamatan', 'value' => 'Parungponteng', 'group' => 'general', 'tipe' => 'text', 'label' => 'Kecamatan'],
            ['key' => 'desa_kabupaten', 'value' => 'Tasikmalaya', 'group' => 'general', 'tipe' => 'text', 'label' => 'Kabupaten'],
            ['key' => 'desa_provinsi', 'value' => 'Jawa Barat', 'group' => 'general', 'tipe' => 'text', 'label' => 'Provinsi'],
            ['key' => 'desa_kode_pos', 'value' => '46183', 'group' => 'general', 'tipe' => 'text', 'label' => 'Kode Pos'],
            ['key' => 'desa_telepon', 'value' => '', 'group' => 'general', 'tipe' => 'text', 'label' => 'Telepon'],
            ['key' => 'desa_email', 'value' => 'desacibungur@gmail.com', 'group' => 'general', 'tipe' => 'text', 'label' => 'Email'],
            ['key' => 'desa_lat', 'value' => '-7.3521', 'group' => 'general', 'tipe' => 'text', 'label' => 'Latitude'],
            ['key' => 'desa_lng', 'value' => '108.1234', 'group' => 'general', 'tipe' => 'text', 'label' => 'Longitude'],
            // Social Media
            ['key' => 'sosmed_facebook', 'value' => '', 'group' => 'social', 'tipe' => 'text', 'label' => 'Facebook'],
            ['key' => 'sosmed_instagram', 'value' => '', 'group' => 'social', 'tipe' => 'text', 'label' => 'Instagram'],
            ['key' => 'sosmed_youtube', 'value' => '', 'group' => 'social', 'tipe' => 'text', 'label' => 'YouTube'],
            // SEO
            ['key' => 'seo_title', 'value' => 'Website Resmi Desa Cibungur - Kecamatan Parungponteng', 'group' => 'seo', 'tipe' => 'text', 'label' => 'SEO Title'],
            ['key' => 'seo_description', 'value' => 'Website resmi Desa Cibungur, Kecamatan Parungponteng, Kabupaten Tasikmalaya. Layanan informasi dan administrasi desa.', 'group' => 'seo', 'tipe' => 'textarea', 'label' => 'SEO Description'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }

        $this->command->info('  → Settings created');
    }

    private function seedKonten(): void
    {
        // Slider default
        Slider::firstOrCreate(
            ['judul' => 'Selamat Datang di Desa Cibungur'],
            [
                'subtitle'    => 'Desa Cibungur, Kecamatan Parungponteng',
                'deskripsi'   => 'Membangun Desa, Mensejahterakan Masyarakat',
                'gambar'      => 'sliders/default-hero.jpg',
                'button_text' => 'Pelajari Lebih Lanjut',
                'urutan'      => 1,
                'is_active'   => true,
            ]
        );

        $this->command->info('  → Konten default created');
    }

    private function seedPerangkatDesa(): void
    {
        $perangkat = [
            ['nama' => 'Nama Kepala Desa', 'jabatan' => 'Kepala Desa', 'urutan' => 1],
            ['nama' => 'Nama Sekretaris', 'jabatan' => 'Sekretaris Desa', 'urutan' => 2],
            ['nama' => 'Nama Kasi Pemerintahan', 'jabatan' => 'Kasi Pemerintahan', 'urutan' => 3],
            ['nama' => 'Nama Kasi Kesejahteraan', 'jabatan' => 'Kasi Kesejahteraan', 'urutan' => 4],
            ['nama' => 'Nama Kasi Pelayanan', 'jabatan' => 'Kasi Pelayanan', 'urutan' => 5],
            ['nama' => 'Nama Kaur Tata Usaha', 'jabatan' => 'Kaur Tata Usaha & Umum', 'urutan' => 6],
            ['nama' => 'Nama Kaur Keuangan', 'jabatan' => 'Kaur Keuangan', 'urutan' => 7],
            ['nama' => 'Nama Kaur Perencanaan', 'jabatan' => 'Kaur Perencanaan', 'urutan' => 8],
        ];

        foreach ($perangkat as $data) {
            PerangkatDesa::firstOrCreate(['jabatan' => $data['jabatan']], $data);
        }

        $this->command->info('  → Perangkat desa created');
    }

    private function seedApbdes(): void
    {
        $tahun = ApbdesTahun::firstOrCreate(
            ['tahun' => date('Y')],
            ['status' => 'aktif', 'total_pendapatan' => 850000000, 'total_belanja' => 850000000]
        );

        $items = [
            // Pendapatan
            ['jenis' => 'pendapatan', 'kategori' => 'Dana Desa', 'nama_kegiatan' => 'Dana Desa APBN', 'anggaran' => 700000000, 'realisasi' => 525000000],
            ['jenis' => 'pendapatan', 'kategori' => 'ADD', 'nama_kegiatan' => 'Alokasi Dana Desa', 'anggaran' => 100000000, 'realisasi' => 75000000],
            ['jenis' => 'pendapatan', 'kategori' => 'PAD', 'nama_kegiatan' => 'Pendapatan Asli Desa', 'anggaran' => 50000000, 'realisasi' => 30000000],
            // Belanja
            ['jenis' => 'belanja', 'kategori' => 'Penyelenggaraan Pemerintahan', 'nama_kegiatan' => 'Belanja Pegawai', 'anggaran' => 200000000, 'realisasi' => 150000000],
            ['jenis' => 'belanja', 'kategori' => 'Pelaksanaan Pembangunan', 'nama_kegiatan' => 'Infrastruktur Jalan Desa', 'anggaran' => 350000000, 'realisasi' => 200000000],
            ['jenis' => 'belanja', 'kategori' => 'Pemberdayaan Masyarakat', 'nama_kegiatan' => 'Program PKK & Posyandu', 'anggaran' => 150000000, 'realisasi' => 80000000],
            ['jenis' => 'belanja', 'kategori' => 'Penanggulangan Bencana', 'nama_kegiatan' => 'Cadangan Bencana', 'anggaran' => 150000000, 'realisasi' => 0],
        ];

        foreach ($items as $idx => $item) {
            ApbdesItem::firstOrCreate(
                ['tahun_id' => $tahun->id, 'nama_kegiatan' => $item['nama_kegiatan']],
                array_merge($item, [
                    'tahun_id'   => $tahun->id,
                    'urutan'     => $idx + 1,
                    'persentase' => $item['anggaran'] > 0
                        ? round(($item['realisasi'] / $item['anggaran']) * 100, 2)
                        : 0,
                ])
            );
        }

        $this->command->info('  → APBDes data created');
    }

    private function seedFaq(): void
    {
        $faqs = [
            ['pertanyaan' => 'Bagaimana cara membuat surat keterangan domisili?', 'jawaban' => 'Datang ke kantor desa dengan membawa KTP dan KK asli. Isi formulir permohonan dan tunggu proses pembuatan surat.', 'kategori' => 'Pelayanan'],
            ['pertanyaan' => 'Jam berapa kantor desa buka?', 'jawaban' => 'Kantor Desa Cibungur buka setiap hari Senin s/d Jumat pukul 08.00 - 16.00 WIB.', 'kategori' => 'Umum'],
            ['pertanyaan' => 'Bagaimana cara melaporkan pengaduan?', 'jawaban' => 'Anda dapat membuat laporan pengaduan melalui menu Pengaduan di website ini, atau datang langsung ke kantor desa.', 'kategori' => 'Pengaduan'],
        ];

        foreach ($faqs as $idx => $faq) {
            Faq::firstOrCreate(
                ['pertanyaan' => $faq['pertanyaan']],
                array_merge($faq, ['urutan' => $idx + 1, 'is_active' => true])
            );
        }

        $this->command->info('  → FAQ created');
    }
}
