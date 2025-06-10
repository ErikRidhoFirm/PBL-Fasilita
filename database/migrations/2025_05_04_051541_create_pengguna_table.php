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
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id('id_pengguna');
            $table->unsignedBigInteger('id_peran')->index();
            $table->string('no_induk', 20)->unique();
            $table->string('username', 20)->unique();
            $table->string('nama', 40);
            $table->string('password');
            $table->string('foto_profile')->nullable();
            $table->timestamps();

            $table->foreign('id_peran')->references('id_peran')->on('peran')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
    }
};
