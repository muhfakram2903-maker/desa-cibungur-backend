<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Pengaduan
        Schema::create('kategori_pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Pengaduan Masyarakat
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_tiket', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            // Data pelapor (untuk laporan anonim)
            $table->string('nama_pelapor', 150)->nullable();
            $table->string('hp_pelapor', 20)->nullable();
            $table->string('email_pelapor', 100)->nullable();

            $table->string('judul', 300);
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_pengaduan')->nullOnDelete();
            $table->string('lokasi', 300)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->longText('deskripsi');
            $table->json('foto')->nullable();
            $table->string('video')->nullable();

            $table->enum('status', ['menunggu', 'diproses', 'selesai', 'ditolak'])->default('menunggu');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi', 'urgent'])->default('sedang');

            $table->foreignId('ditangani_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan_admin')->nullable();

            $table->timestamps();

            $table->index('nomor_tiket');
            $table->index('status');
            $table->index('kategori_id');
            $table->index('user_id');
        });

        // Respon Pengaduan (dari admin)
        Schema::create('pengaduan_respon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->longText('konten');
            $table->json('lampiran')->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();

            $table->index('pengaduan_id');
        });

        // Timeline / Riwayat Status Pengaduan
        Schema::create('pengaduan_timeline', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained('pengaduan')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status_lama', 20)->nullable();
            $table->string('status_baru', 20);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->index('pengaduan_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaduan_timeline');
        Schema::dropIfExists('pengaduan_respon');
        Schema::dropIfExists('pengaduan');
        Schema::dropIfExists('kategori_pengaduan');
    }
};
