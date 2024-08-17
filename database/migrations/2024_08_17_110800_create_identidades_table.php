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
        Schema::create('identidades', function (Blueprint $table) {
            $table->id();
            $table->string('nombres', 50);
            $table->char('dni', 8)->unique();
            $table->string('apellido_paterno', 50);
            $table->string('apellido_materno', 50);
            $table->date('fecha_nacimiento');
            $table->char('nro_colegiatura', 8)->unique();
            $table->string('foto_perfil', 250)->nullable();
            $table->string('doc_dni', 250)->nullable();
            $table->string('doc_titulo', 250)->nullable();
            $table->string('doc_colegiatura', 250)->nullable();
            $table->string('doc_karnet', 250)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identidades');
    }
};
