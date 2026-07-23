<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Agenda
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 300);
            $table->string('slug', 350)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('lokasi', 300)->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('poster')->nullable();
            $table->string('link_pendaftaran')->nullable();
            $table->integer('kuota')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'done'])->default('published');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('tanggal_mulai');
            $table->index('status');
        });

        // Pengumuman
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 300);
            $table->longText('konten');
            $table->string('lampiran')->nullable();
            $table->enum('prioritas', ['rendah', 'normal', 'tinggi', 'urgent'])->default('normal');
            $table->date('tanggal_terbit')->nullable();
            $table->date('tanggal_expired')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('tanggal_expired');
            $table->index('prioritas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
        Schema::dropIfExists('agenda');
    }
};
