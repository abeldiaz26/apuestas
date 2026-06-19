<?php

use App\Http\Controllers\ApuestaController;
use App\Http\Controllers\PartidoController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TournamentController;
use App\Models\Apuesta;
use App\Models\Partido;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard', [
            'torneosCount' => Tournament::count(),
            'equiposCount' => Team::count(),
            'partidosCount' => Partido::count(),
            'apuestasCount' => Apuesta::count(),
        ]);
    })->name('dashboard');

    Route::resource('torneos', TournamentController::class)->only(['index', 'store', 'update']);
    Route::resource('equipos', TeamController::class)->only(['index', 'store', 'update']);
    Route::resource('partidos', PartidoController::class)->only(['index', 'store', 'update']);
    Route::resource('apuestas', ApuestaController::class)->only(['index', 'store', 'update']);
});

require __DIR__.'/settings.php';
