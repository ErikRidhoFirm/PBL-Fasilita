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
        Schema::create('lantai', function (Blueprint $table) {
            $table->id('id_lantai');
            $table->unsignedBigInteger('id_gedung')->index();
            $table->string('nomor_lantai');
            $table->timestamps();

            $table->foreign('id_gedung')->references('id_gedung')->on('gedung')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lantai');
    }
};
