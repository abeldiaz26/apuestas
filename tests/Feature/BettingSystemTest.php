<?php

use App\Models\Apuesta;
use App\Models\Partido;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Support\Carbon;

it('allows an authenticated user to create a torneo', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/torneos', [
        'name' => 'Copa de Prueba',
    ]);

    $response->assertRedirect();
    expect(Tournament::where('name', 'Copa de Prueba')->exists())->toBeTrue();
});

it('updates an existing bet for the same user and partido before match start', function () {
    $user = User::factory()->create();
    $tournament = Tournament::create(['name' => 'Copa Test']);
    $team1 = Team::create(['name' => 'Equipo A', 'flag' => 'A', 'tournament_id' => $tournament->id]);
    $team2 = Team::create(['name' => 'Equipo B', 'flag' => 'B', 'tournament_id' => $tournament->id]);
    $partido = Partido::create([
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->addHour(),
        'estado' => 'pendiente',
        'torneo_id' => $tournament->id,
    ]);

    $this->actingAs($user)->post('/apuestas', [
        'partido_id' => $partido->id,
        'user_id' => $user->id,
        'equipo1_puntaje' => 1,
        'equipo2_puntaje' => 0,
    ]);

    $this->actingAs($user)->post('/apuestas', [
        'partido_id' => $partido->id,
        'user_id' => $user->id,
        'equipo1_puntaje' => 2,
        'equipo2_puntaje' => 1,
    ]);

    expect(Apuesta::where('partido_id', $partido->id)->where('user_id', $user->id)->count())->toBe(1);
    expect(Apuesta::where('partido_id', $partido->id)->where('user_id', $user->id)->first()->equipo1_puntaje)->toBe(2);
});

it('prevents editing a bet after the partido has started', function () {
    $user = User::factory()->create();
    $tournament = Tournament::create(['name' => 'Copa Test']);
    $team1 = Team::create(['name' => 'Equipo A', 'flag' => 'A', 'tournament_id' => $tournament->id]);
    $team2 = Team::create(['name' => 'Equipo B', 'flag' => 'B', 'tournament_id' => $tournament->id]);
    $partido = Partido::create([
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->subHour(),
        'estado' => 'en curso',
        'torneo_id' => $tournament->id,
    ]);
    $apuesta = Apuesta::create([
        'partido_id' => $partido->id,
        'user_id' => $user->id,
        'equipo1_puntaje' => 1,
        'equipo2_puntaje' => 2,
        'puntos' => 0,
    ]);

    $this->actingAs($user)->patch('/apuestas/'.$apuesta->id, [
        'equipo1_puntaje' => 2,
        'equipo2_puntaje' => 1,
    ]);

    expect($apuesta->fresh()->equipo1_puntaje)->toBe(1);
    expect($apuesta->fresh()->equipo2_puntaje)->toBe(2);
});

it('recalculates bet points when the partido result is entered', function () {
    $user = User::factory()->create();
    $tournament = Tournament::create(['name' => 'Copa Test']);
    $team1 = Team::create(['name' => 'Equipo A', 'flag' => 'A', 'tournament_id' => $tournament->id]);
    $team2 = Team::create(['name' => 'Equipo B', 'flag' => 'B', 'tournament_id' => $tournament->id]);
    $partido = Partido::create([
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->addHour(),
        'estado' => 'pendiente',
        'torneo_id' => $tournament->id,
    ]);
    $apuesta = Apuesta::create([
        'partido_id' => $partido->id,
        'user_id' => $user->id,
        'equipo1_puntaje' => 2,
        'equipo2_puntaje' => 1,
        'puntos' => 0,
    ]);

    $this->actingAs($user)->patch('/partidos/'.$partido->id, [
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->addHour()->format('Y-m-d H:i:s'),
        'equipo1_puntaje' => 2,
        'equipo2_puntaje' => 1,
        'estado' => 'finalizado',
        'torneo_id' => $tournament->id,
    ]);

    expect($apuesta->fresh()->puntos)->toBe(2);
});

it('shows only upcoming matches for tournaments the authenticated user is enabled for', function () {
    $user = User::factory()->create();
    $allowedTournament = Tournament::create(['name' => 'Habilitado']);
    $blockedTournament = Tournament::create(['name' => 'Bloqueado']);
    $user->tournaments()->attach($allowedTournament->id);

    $team1 = Team::create(['name' => 'Equipo A', 'flag' => 'A', 'tournament_id' => $allowedTournament->id]);
    $team2 = Team::create(['name' => 'Equipo B', 'flag' => 'B', 'tournament_id' => $allowedTournament->id]);
    $team3 = Team::create(['name' => 'Equipo C', 'flag' => 'C', 'tournament_id' => $blockedTournament->id]);
    $team4 = Team::create(['name' => 'Equipo D', 'flag' => 'D', 'tournament_id' => $blockedTournament->id]);

    $partidoAllowed = Partido::create([
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->addHour(),
        'estado' => 'pendiente',
        'torneo_id' => $allowedTournament->id,
    ]);

    $partidoBlocked = Partido::create([
        'equipo1_id' => $team3->id,
        'equipo2_id' => $team4->id,
        'fecha_hora' => Carbon::now()->addHour(),
        'estado' => 'pendiente',
        'torneo_id' => $blockedTournament->id,
    ]);

    $response = $this->actingAs($user)->get('/apuestas');

    $response->assertOk();
    $response->assertSee($partidoAllowed->equipo1->name);
    $response->assertDontSee($partidoBlocked->equipo1->name);
});

it('prevents betting on partidos from tournaments the user is not enabled for', function () {
    $user = User::factory()->create();
    $allowedTournament = Tournament::create(['name' => 'Habilitado']);
    $blockedTournament = Tournament::create(['name' => 'Bloqueado']);
    $user->tournaments()->attach($allowedTournament->id);

    $team1 = Team::create(['name' => 'Equipo A', 'flag' => 'A', 'tournament_id' => $allowedTournament->id]);
    $team2 = Team::create(['name' => 'Equipo B', 'flag' => 'B', 'tournament_id' => $blockedTournament->id]);

    $partidoBlocked = Partido::create([
        'equipo1_id' => $team1->id,
        'equipo2_id' => $team2->id,
        'fecha_hora' => Carbon::now()->addHour(),
        'estado' => 'pendiente',
        'torneo_id' => $blockedTournament->id,
    ]);

    $response = $this->actingAs($user)->post('/apuestas', [
        'partido_id' => $partidoBlocked->id,
        'equipo1_puntaje' => 1,
        'equipo2_puntaje' => 0,
    ]);

    $response->assertSessionHasErrors('partido_id');
    expect(Apuesta::where('partido_id', $partidoBlocked->id)->count())->toBe(0);
});
