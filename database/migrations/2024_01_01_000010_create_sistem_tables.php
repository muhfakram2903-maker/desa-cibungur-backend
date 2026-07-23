<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Settings Website
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->string('group', 50)->default('general');
            $table->enum('tipe', ['text', 'textarea', 'number', 'boolean', 'json', 'image', 'file', 'color', 'select'])->default('text');
            $table->string('label', 200)->nullable();
            $table->text('deskripsi')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();

            $table->index('group');
        });

        // Slider / Banner
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 200)->nullable();
            $table->string('subtitle', 300)->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar');
            $table->string('url', 300)->nullable();
            $table->string('button_text', 50)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // FAQ
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('pertanyaan', 500);
            $table->longText('jawaban');
            $table->string('kategori', 100)->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('kategori');
        });

        // Audit Log
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('event', 50); // created, updated, deleted, login, logout
            $table->string('auditable_type', 100)->nullable();
            $table->unsignedBigInteger('auditable_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('url', 500)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index(['auditable_type', 'auditable_id']);
            $table->index('event');
            $table->index('created_at');
        });

        // Visitor Log
        Schema::create('visitor_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 45);
            $table->string('user_agent', 500)->nullable();
            $table->string('page', 500)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('ip');
            $table->index('created_at');
        });

        // Kontak Masuk
        Schema::create('kontak_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('email', 150);
            $table->string('no_hp', 20)->nullable();
            $table->string('subjek', 300);
            $table->longText('pesan');
            $table->enum('status', ['unread', 'read', 'replied'])->default('unread');
            $table->text('balasan')->nullable();
            $table->timestamp('replied_at')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kontak_masuk');
        Schema::dropIfExists('visitor_logs');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('sliders');
        Schema::dropIfExists('settings');
    }
};
