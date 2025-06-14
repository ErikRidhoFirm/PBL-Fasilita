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
        Schema::create('ruangan', function (Blueprint $table) {
            $table->id('id_ruangan');
            $table->unsignedBigInteger('id_lantai')->index();
            $table->string('kode_ruangan', 10);
            $table->string('nama_ruangan', 50);
            $table->timestamps();

            $table->foreign('id_lantai')->references('id_lantai')->on('lantai')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruangan');
    }
};
