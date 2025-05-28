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
        Schema::create('skor_kriteria_laporan', function (Blueprint $table) {
            $table->id('id_skor_kriteria_laporan');
            $table->unsignedBigInteger('id_penilaian')->index();
            $table->unsignedBigInteger('id_kriteria')->index();
            $table->integer('nilai_mentah');
            $table->timestamps();

            $table->foreign('id_penilaian')->references('id_penilaian')->on('penilaian')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skor_kriteria_laporan');
    }
};
