<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo1_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->foreignId('equipo2_id')->nullable()->constrained('teams')->onDelete('cascade');
            $table->dateTime('fecha_hora');
            $table->integer('equipo1_puntaje')->nullable();
            $table->integer('equipo2_puntaje')->nullable();
            $table->string('estado')->default('pendiente');
            $table->foreignId('torneo_id')->constrained('tournaments')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
