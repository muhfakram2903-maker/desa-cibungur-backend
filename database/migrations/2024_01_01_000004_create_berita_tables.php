<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori Berita
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('warna', 10)->default('#2E7D32');
            $table->string('icon', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tags Berita
        Schema::create('tag_berita', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('slug', 120)->unique();
            $table->timestamps();
        });

        // Berita
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 300);
            $table->string('slug', 350)->unique();
            $table->longText('konten');
            $table->text('excerpt')->nullable();
            $table->string('thumbnail')->nullable();
            $table->foreignId('kategori_id')->nullable()->constrained('kategori_berita')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->unsignedBigInteger('view_count')->default(0);
            // SEO Fields
            $table->string('seo_title', 200)->nullable();
            $table->text('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comment')->default(true);
            $table->softDeletes();
            $table->timestamps();

            $table->index('slug');
            $table->index('status');
            $table->index('published_at');
            $table->index('is_featured');
            $table->index('kategori_id');
        });

        // Pivot: Berita - Tag
        Schema::create('berita_tag', function (Blueprint $table) {
            $table->foreignId('berita_id')->constrained('berita')->onDelete('cascade');
            $table->foreignId('tag_id')->constrained('tag_berita')->onDelete('cascade');
            $table->primary(['berita_id', 'tag_id']);
        });

        // Komentar Berita
        Schema::create('komentar_berita', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nama', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->text('konten');
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->foreignId('parent_id')->nullable()->constrained('komentar_berita')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('berita_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komentar_berita');
        Schema::dropIfExists('berita_tag');
        Schema::dropIfExists('berita');
        Schema::dropIfExists('tag_berita');
        Schema::dropIfExists('kategori_berita');
    }
};
