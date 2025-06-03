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
        Schema::create('penilaian_pengguna', function (Blueprint $table) {
            $table->id('id_penilaian_pengguna');
            $table->unsignedBigInteger('id_laporan_fasilitas')->index();
            $table->unsignedBigInteger('id_pengguna')->index(); // id mahasiswa, dosen, tendik
            $table->integer('nilai');
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->foreign('id_laporan_fasilitas')->references('id_laporan_fasilitas')->on('laporan_fasilitas')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_pengguna');
    }
};
