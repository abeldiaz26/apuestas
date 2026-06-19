<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(): View
    {
        return view('equipos.index', [
            'equipos' => Team::with('tournament')->orderBy('name')->get(),
            'torneos' => Tournament::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'flag' => ['nullable', 'string', 'max:255'],
            'tournament_id' => ['required', 'exists:tournaments,id'],
        ]);

        Team::create($request->only('name', 'flag', 'tournament_id'));

        return back();
    }

    public function update(Request $request, Team $equipo): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'flag' => ['nullable', 'string', 'max:255'],
            'tournament_id' => ['required', 'exists:tournaments,id'],
        ]);

        $equipo->update($request->only('name', 'flag', 'tournament_id'));

        return back();
    }
}
