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
        Schema::create('skor_topsis', function (Blueprint $table) {
            $table->id('id_skor_topsis');
            $table->unsignedBigInteger('id_skor_tipe')->index();
            $table->unsignedBigInteger('id_laporan_fasilitas')->index();
            $table->float('skor');
            $table->timestamps();

            $table->foreign('id_skor_tipe')->references('id_skor_tipe')->on('skor_tipe')->onDelete('cascade');
            $table->foreign('id_laporan_fasilitas')->references('id_laporan_fasilitas')->on('laporan_fasilitas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skor_topsis');
    }
};
