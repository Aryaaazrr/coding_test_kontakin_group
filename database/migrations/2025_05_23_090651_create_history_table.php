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
        Schema::create('history', function (Blueprint $table) {
            $table->uuid('id_history')->primary();
            $table->foreignUuid('id_article')->constrained('article', 'id_article')->onUpdate('cascade')->onDelete('cascade');
            // $table->foreignUuid('id_users')->constrained('users', 'id_users')->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedBigInteger('id_users');
            $table->foreign('id_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignUuid('id_mahasiswa')->constrained('mahasiswa', 'id_mahasiswa')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('aksi', ['Upload', 'Approve', 'Revision']);
            $table->text('catatan')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history');
    }
};