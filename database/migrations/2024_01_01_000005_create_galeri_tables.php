<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Album Galeri
        Schema::create('galeri_album', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200);
            $table->string('slug', 220)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->string('kategori', 50)->default('umum');
            $table->integer('urutan')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
        });

        // Item Galeri (foto/video)
        Schema::create('galeri_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('album_id')->constrained('galeri_album')->onDelete('cascade');
            $table->string('judul', 200)->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('tipe', ['foto', 'video'])->default('foto');
            $table->string('file')->nullable();
            $table->string('url_video')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();

            $table->index('album_id');
            $table->index('tipe');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeri_item');
        Schema::dropIfExists('galeri_album');
    }
};
