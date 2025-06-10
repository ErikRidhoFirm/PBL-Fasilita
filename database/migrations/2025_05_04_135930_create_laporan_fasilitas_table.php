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
            $table->unsignedBigInteger('id_kategori_kerusakan')->index();
            $table->unsignedBigInteger('id_status')->index();
            $table->integer('jumlah_rusak');
            $table->string('path_foto')->nullable();
            $table->text('deskripsi', 100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('id_laporan')->references('id_laporan')->on('laporan')->onDelete('cascade');
            $table->foreign('id_fasilitas')->references('id_fasilitas')->on('fasilitas')->onDelete('cascade');
            $table->foreign('id_kategori_kerusakan')->references('id_kategori_kerusakan')->on('kategori_kerusakan')->onDelete('cascade');
            $table->foreign('id_status')->references('id_status')->on('status')->onDelete('cascade');
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
