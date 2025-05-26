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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->uuid('id_mahasiswa')->primary();
            // $table->foreignUuid('id_users')->constrained('users', 'id_users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('nim')->unique()->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('prodi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
