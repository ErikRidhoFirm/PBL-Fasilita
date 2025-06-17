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
        Schema::create('laporan_fasilitas', function (Blueprint $table) {
            $table->id('id_laporan_fasilitas');
            $table->unsignedBigInteger('id_laporan')->index();
            $table->unsignedBigInteger('id_fasilitas')->index();
            $table->unsignedBigInteger('id_status')->index();
            $table->string('path_foto')->nullable();
            $table->text('deskripsi', 100);
            $table->unsignedBigInteger('id_tingkat_kerusakan')->index();
            $table->unsignedBigInteger('id_dampak_pengguna')->index();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_laporan')->references('id_laporan')->on('laporan')->onDelete('cascade');
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('status')->onDelete('cascade');
            $table->foreign('id_tingkat_kerusakan')->references('id_skoring_kriteria')->on('skoring_kriteria')->onDelete('cascade');
            $table->foreign('id_dampak_pengguna')->references('id_skoring_kriteria')->on('skoring_kriteria')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_fasilitas');
    }
};
