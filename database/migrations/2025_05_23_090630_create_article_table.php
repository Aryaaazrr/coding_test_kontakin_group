<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->uuid('id_article')->primary();
            $table->foreignUuid('id_mahasiswa')->constrained('mahasiswa', 'id_mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_dosen')->constrained('dosen', 'id_dosen')->onUpdate('cascade')->onDelete('cascade');
            $table->string('judul');
            $table->enum('tipe', ['Laporan PKL', 'Skripsi']);
            $table->string('file');
            $table->boolean('disetujui')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
};
