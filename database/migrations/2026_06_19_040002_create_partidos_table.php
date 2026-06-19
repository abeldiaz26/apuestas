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
        Schema::create('partidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo1_id')->constrained('teams')->cascadeOnDelete();
            $table->foreignId('equipo2_id')->constrained('teams')->cascadeOnDelete();
            $table->dateTime('fecha_hora');
            $table->integer('equipo1_puntaje')->nullable();
            $table->integer('equipo2_puntaje')->nullable();
            $table->string('estado')->default('pendiente');
            $table->foreignId('torneo_id')->constrained('tournaments')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partidos');
    }
};
