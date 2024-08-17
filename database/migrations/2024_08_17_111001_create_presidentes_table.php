<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('presidentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('identidad_id');
            $table->unsignedBigInteger('equipo_id')->unique()->nullable();
            $table->foreign('identidad_id')->references('id')->on('identidades')->onDelete('cascade');
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presidentes');
    }
};
