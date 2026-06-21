<?php

namespace App\Http\Controllers;

use App\Models\Apuesta;
use App\Models\Partido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ApuestaController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $torneoIds = $user->tournaments()->pluck('tournaments.id');

        return view('apuestas.index', [
            'apuestas' => Apuesta::with(['user', 'partido.equipo1', 'partido.equipo2'])
                ->where('user_id', $user->id)
                ->whereHas('partido', fn ($query) => $query->whereIn('torneo_id', $torneoIds))
                ->orderByDesc('created_at')
                ->get(),
            'partidos' => Partido::with(['equipo1', 'equipo2', 'torneo'])
                ->where('fecha_hora', '>', now())
                ->whereIn('torneo_id', $torneoIds)
                ->orderBy('fecha_hora')
                ->get(),
            'user' => $user,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'partido_id' => ['required', 'exists:partidos,id'],
            'equipo1_puntaje' => ['required', 'integer', 'min:0'],
            'equipo2_puntaje' => ['required', 'integer', 'min:0'],
        ]);

        $user = Auth::user();
        $partido = Partido::findOrFail($request->input('partido_id'));

        if (! $user->tournaments()->where('tournaments.id', $partido->torneo_id)->exists()) {
            throw ValidationException::withMessages([
                'partido_id' => 'No estás habilitado para apostar en el torneo de este partido.',
            ]);
        }

        if ($partido->hasStarted()) {
            throw ValidationException::withMessages([
                'partido_id' => 'Ya no se pueden crear o editar apuestas para este partido.',
            ]);
        }

        $apuesta = Apuesta::firstOrNew([
            'partido_id' => $partido->id,
            'user_id' => $user->id,
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
        if ($apuesta->user_id !== Auth::id()) {
            abort(403);
        }

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
