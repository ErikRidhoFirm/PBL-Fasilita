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
        Schema::create('kategori_kerusakan', function (Blueprint $table) {
            $table->id('id_kategori_kerusakan');
            $table->string('kode_kerusakan', 10)->unique();
            $table->string('nama_kerusakan', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_kerusakan');
    }
};
