<?php

namespace App\Http\Controllers;

use App\Models\Partido;
use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PartidoController extends Controller
{
    public function index(): View
    {
        return view('partidos.index', [
            'partidos' => Partido::with(['equipo1', 'equipo2', 'torneo'])->orderBy('fecha_hora')->get(),
            'equipos' => Team::orderBy('name')->get(),
            'torneos' => Tournament::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'equipo1_id' => ['required', 'exists:teams,id'],
            'equipo2_id' => ['required', 'exists:teams,id', 'different:equipo1_id'],
            'fecha_hora' => ['required', 'date'],
            'equipo1_puntaje' => ['nullable', 'integer', 'min:0'],
            'equipo2_puntaje' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', 'string', 'max:50'],
            'torneo_id' => ['required', 'exists:tournaments,id'],
        ]);

        Partido::create($request->only([
            'equipo1_id',
            'equipo2_id',
            'fecha_hora',
            'equipo1_puntaje',
            'equipo2_puntaje',
            'estado',
            'torneo_id',
        ]));

        return back();
    }

    public function update(Request $request, Partido $partido): RedirectResponse
    {
        $request->validate([
            'equipo1_id' => ['required', 'exists:teams,id'],
            'equipo2_id' => ['required', 'exists:teams,id', 'different:equipo1_id'],
            'fecha_hora' => ['required', 'date'],
            'equipo1_puntaje' => ['nullable', 'integer', 'min:0'],
            'equipo2_puntaje' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', 'string', 'max:50'],
            'torneo_id' => ['required', 'exists:tournaments,id'],
        ]);

        $partido->update($request->only([
            'equipo1_id',
            'equipo2_id',
            'fecha_hora',
            'equipo1_puntaje',
            'equipo2_puntaje',
            'estado',
            'torneo_id',
        ]));

        if ($partido->hasResult()) {
            $partido->recalculateApuestaPoints();
        }

        return back();
    }
}
