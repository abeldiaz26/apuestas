<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tournament;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', [
            'users' => User::with('tournaments')->orderBy('name')->get(),
            'torneos' => Tournament::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users'],
            'role' => ['required', 'string', 'max:50'],
        ]);

        $password = $request->input('password', $request->input('code'));

        User::create([
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'email' => $request->input('email'),
            'password' => Hash::make($password),
            'role' => $request->input('role', 'user'),
        ]);

        return back();
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:users,code,'.$user->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'string', 'max:50'],
        ]);

        $user->update($request->only(['name', 'code', 'email', 'role']));

        return back();
    }

    public function syncTournaments(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'torneos' => ['nullable', 'array'],
            'torneos.*' => ['integer', 'exists:tournaments,id'],
        ]);

        $user->tournaments()->sync($request->input('torneos', []));

        return back();
    }
}
