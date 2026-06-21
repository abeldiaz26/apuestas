<?php

namespace App\Http\Controllers;

use App\Models\Apuesta;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TournamentController extends Controller
{
    public function index(): View
    {
        return view('torneos.index', [
            'torneos' => Tournament::with('users')->orderBy('name')->get(),
            'users' => User::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Tournament::create($request->only('name'));

        return back();
    }

    public function update(Request $request, Tournament $torneo): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $torneo->update($request->only('name'));

        return back();
    }

    public function syncUsers(Request $request, Tournament $torneo): RedirectResponse
    {
        $request->validate([
            'users' => ['nullable', 'array'],
            'users.*' => ['integer', 'exists:users,id'],
        ]);

        $torneo->users()->sync($request->input('users', []));

        return back();
    }

    public function myTournaments(): View
    {
        $user = auth()->user();

        $torneos = $user->tournaments()->with(['teams', 'partidos' => function ($q) use ($user) {
            $q->where('fecha_hora', '>', now())
                ->orderBy('fecha_hora')
                ->with(['equipo1', 'equipo2', 'apuestas' => function ($q2) use ($user) {
                    $q2->where('user_id', $user->id);
                }]);
        }])->get();

        $torneoStats = [];

        foreach ($torneos as $torneo) {
            $rankings = Apuesta::selectRaw('user_id, SUM(puntos) as total_puntos')
                ->whereHas('partido', fn ($query) => $query->where('torneo_id', $torneo->id))
                ->groupBy('user_id')
                ->orderByDesc('total_puntos')
                ->get()
                ->load('user');

            $userRank = $rankings->search(fn ($row) => $row->user_id === $user->id);

            $torneoStats[$torneo->id] = [
                'rankings' => $rankings,
                'user_total' => $rankings->firstWhere('user_id', $user->id)?->total_puntos ?? 0,
                'user_rank' => $userRank === false ? null : $userRank + 1,
            ];
        }

        return view('torneos.mine', [
            'torneos' => $torneos,
            'torneoStats' => $torneoStats,
        ]);
    }
}
