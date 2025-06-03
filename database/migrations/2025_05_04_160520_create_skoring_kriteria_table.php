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
        Schema::create('skoring_kriteria', function (Blueprint $table) {
            $table->id('id_skoring_kriteria');
            $table->unsignedBigInteger('id_kriteria')->index();
            $table->string('parameter');
            $table->integer('nilai_referensi');
            $table->timestamps();

    $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skoring_kriteria');
    }
};
