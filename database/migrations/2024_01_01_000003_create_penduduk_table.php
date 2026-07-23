<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nomor_kk', 16)->index();
            $table->string('nama', 200);
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->nullOnDelete();
            $table->foreignId('rw_id')->nullable()->constrained('rw')->nullOnDelete();
            $table->foreignId('rt_id')->nullable()->constrained('rt')->nullOnDelete();
            $table->foreignId('agama_id')->nullable()->constrained('agama')->nullOnDelete();
            $table->foreignId('pendidikan_id')->nullable()->constrained('pendidikan')->nullOnDelete();
            $table->foreignId('pekerjaan_id')->nullable()->constrained('pekerjaan')->nullOnDelete();
            $table->enum('status_kawin', ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'])->default('belum_kawin');
            $table->enum('status_keluarga', ['kepala_keluarga', 'istri', 'anak', 'menantu', 'cucu', 'orang_tua', 'mertua', 'famili_lain', 'lainnya'])->default('anak');
            $table->string('no_hp', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('foto')->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->date('tanggal_masuk')->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->string('alasan_keluar')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();

            // Indexes untuk pencarian cepat
            $table->index('nama');
            $table->index('status_aktif');
            $table->index('jenis_kelamin');
            $table->index('dusun_id');
            $table->index(['rw_id', 'rt_id']);
            $table->index('tanggal_lahir');
        });

        // Riwayat perubahan data penduduk
        Schema::create('penduduk_riwayat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->constrained('penduduk')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('field_changed', 50);
            $table->text('nilai_lama')->nullable();
            $table->text('nilai_baru')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->index('penduduk_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penduduk_riwayat');
        Schema::dropIfExists('penduduk');
    }
};
