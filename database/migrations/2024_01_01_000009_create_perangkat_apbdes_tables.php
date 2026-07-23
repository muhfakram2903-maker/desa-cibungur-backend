<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Perangkat Desa
        Schema::create('perangkat_desa', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('jabatan', 150);
            $table->string('nip', 30)->nullable();
            $table->string('foto')->nullable();
            $table->text('riwayat')->nullable();
            $table->date('masa_jabatan_mulai')->nullable();
            $table->date('masa_jabatan_selesai')->nullable();
            $table->string('pendidikan_terakhir', 100)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('jabatan');
            $table->index('is_active');
        });

        // APBDes - Tahun Anggaran
        Schema::create('apbdes_tahun', function (Blueprint $table) {
            $table->id();
            $table->year('tahun')->unique();
            $table->enum('status', ['draft', 'aktif', 'closed'])->default('draft');
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_belanja', 15, 2)->default(0);
            $table->decimal('total_realisasi_pendapatan', 15, 2)->default(0);
            $table->decimal('total_realisasi_belanja', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // APBDes - Item Anggaran
        Schema::create('apbdes_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_id')->constrained('apbdes_tahun')->onDelete('cascade');
            $table->enum('jenis', ['pendapatan', 'belanja'])->index();
            $table->string('kategori', 200);
            $table->string('nama_kegiatan', 300);
            $table->decimal('anggaran', 15, 2)->default(0);
            $table->decimal('realisasi', 15, 2)->default(0);
            $table->decimal('persentase', 5, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index('tahun_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apbdes_item');
        Schema::dropIfExists('apbdes_tahun');
        Schema::dropIfExists('perangkat_desa');
    }
};
