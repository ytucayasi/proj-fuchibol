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
        Schema::create('jugadores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('equipo_id')->nullable();
            $table->char('nro_colegiatura', 8)->unique();
            $table->char('tipo_jugador', 1);
            $table->string('foto_perfil', 250)->nullable();
            $table->string('doc_dni', 250)->nullable();
            $table->string('doc_titulo', 250)->nullable();
            $table->string('doc_colegiatura', 250)->nullable();
            $table->foreign('equipo_id')->references('id')->on('equipos')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jugadores');
    }
};
