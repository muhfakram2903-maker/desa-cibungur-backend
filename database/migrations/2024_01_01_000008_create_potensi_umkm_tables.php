<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Potensi
        Schema::create('potensi_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->string('icon', 50)->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Potensi Desa
        Schema::create('potensi_desa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->nullable()->constrained('potensi_kategori')->nullOnDelete();
            $table->string('nama', 200);
            $table->string('slug', 220)->unique();
            $table->longText('deskripsi')->nullable();
            $table->json('foto')->nullable();
            $table->string('alamat', 300)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('kontak', 100)->nullable();
            $table->string('website', 200)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('status');
        });

        // UMKM
        Schema::create('umkm', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('slug', 220)->unique();
            $table->string('pemilik', 150);
            $table->foreignId('kategori_id')->nullable()->constrained('potensi_kategori')->nullOnDelete();
            $table->longText('deskripsi')->nullable();
            $table->text('produk_unggulan')->nullable();
            $table->string('alamat', 300)->nullable();
            $table->foreignId('rt_id')->nullable()->constrained('rt')->nullOnDelete();
            $table->foreignId('rw_id')->nullable()->constrained('rw')->nullOnDelete();
            $table->foreignId('dusun_id')->nullable()->constrained('dusun')->nullOnDelete();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->json('foto')->nullable();
            $table->json('foto_produk')->nullable();
            $table->string('no_wa', 20)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('marketplace_url', 300)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('kategori_id');
            $table->index('status');
            $table->index('dusun_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('umkm');
        Schema::dropIfExists('potensi_desa');
        Schema::dropIfExists('potensi_kategori');
    }
};
