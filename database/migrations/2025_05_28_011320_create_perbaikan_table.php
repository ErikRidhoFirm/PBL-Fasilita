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
        Schema::create('perbaikan', function (Blueprint $table) {
            $table->id('id_perbaikan');
            $table->unsignedBigInteger('id_penugasan')->index();
            $table->string('foto_perbaikan');
            $table->enum('jenis_perbaikan', ['perbaikan', 'penggantian'])->default('perbaikan');
            $table->text('deskripsi_perbaikan');
            $table->timestamps();

            $table->foreign('id_penugasan')->references('id_penugasan')->on('penugasan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perbaikan');
    }
};
