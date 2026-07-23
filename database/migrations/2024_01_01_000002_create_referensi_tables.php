<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Referensi Agama
        Schema::create('agama', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->timestamps();
        });

        // Tabel Referensi Pendidikan
        Schema::create('pendidikan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Tabel Referensi Pekerjaan
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
        Schema::dropIfExists('pendidikan');
        Schema::dropIfExists('agama');
    }
};
