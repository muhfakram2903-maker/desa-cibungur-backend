<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Dusun
        Schema::create('dusun', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('kode', 10)->nullable();
            $table->string('ketua_nama', 150)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabel RW
        Schema::create('rw', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 5);
            $table->foreignId('dusun_id')->constrained('dusun')->onDelete('cascade');
            $table->string('ketua_nama', 150)->nullable();
            $table->string('ketua_hp', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('dusun_id');
        });

        // Tabel RT
        Schema::create('rt', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 5);
            $table->foreignId('rw_id')->constrained('rw')->onDelete('cascade');
            $table->string('ketua_nama', 150)->nullable();
            $table->string('ketua_hp', 20)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('rw_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rt');
        Schema::dropIfExists('rw');
        Schema::dropIfExists('dusun');
    }
};
