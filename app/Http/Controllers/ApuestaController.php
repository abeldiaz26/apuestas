<?php

namespace App\Http\Controllers;

use App\Models\Apuesta;
use App\Models\Partido;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ApuestaController extends Controller
{
    public function index(): View
    {
        return view('apuestas.index', [
            'apuestas' => Apuesta::with(['user', 'partido.equipo1', 'partido.equipo2'])->orderByDesc('created_at')->get(),
            'partidos' => Partido::with(['equipo1', 'equipo2'])->where('fecha_hora', '>', now())->orderBy('fecha_hora')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'partido_id' => ['required', 'exists:partidos,id'],
            'user_id' => ['required', 'exists:users,id'],
            'equipo1_puntaje' => ['required', 'integer', 'min:0'],
            'equipo2_puntaje' => ['required', 'integer', 'min:0'],
        ]);

        $partido = Partido::findOrFail($request->input('partido_id'));

        if ($partido->hasStarted()) {
            throw ValidationException::withMessages([
                'partido_id' => 'Ya no se pueden crear o editar apuestas para este partido.',
            ]);
        }

        $apuesta = Apuesta::firstOrNew([
            'partido_id' => $partido->id,
            'user_id' => $request->input('user_id'),
        ]);

        $apuesta->equipo1_puntaje = $request->input('equipo1_puntaje');
        $apuesta->equipo2_puntaje = $request->input('equipo2_puntaje');
        $apuesta->puntos = Apuesta::calculatePoints(
            $apuesta->equipo1_puntaje,
            $apuesta->equipo2_puntaje,
            $partido->equipo1_puntaje,
            $partido->equipo2_puntaje,
        );
        $apuesta->save();

        return back();
    }

    public function update(Request $request, Apuesta $apuesta): RedirectResponse
    {
        if ($apuesta->partido->hasStarted()) {
            throw ValidationException::withMessages([
                'equipo1_puntaje' => 'Ya no se puede editar esta apuesta porque el partido ya comenzó.',
            ]);
        }

        $request->validate([
            'equipo1_puntaje' => ['required', 'integer', 'min:0'],
            'equipo2_puntaje' => ['required', 'integer', 'min:0'],
        ]);

        $apuesta->equipo1_puntaje = $request->input('equipo1_puntaje');
        $apuesta->equipo2_puntaje = $request->input('equipo2_puntaje');
        $apuesta->puntos = Apuesta::calculatePoints(
            $apuesta->equipo1_puntaje,
            $apuesta->equipo2_puntaje,
            $apuesta->partido->equipo1_puntaje,
            $apuesta->partido->equipo2_puntaje,
        );
        $apuesta->save();

        return back();
    }
}
