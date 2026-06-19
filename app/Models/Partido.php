<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'equipo1_id',
    'equipo2_id',
    'fecha_hora',
    'equipo1_puntaje',
    'equipo2_puntaje',
    'estado',
    'torneo_id',
])]
class Partido extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    public function torneo(): BelongsTo
    {
        return $this->belongsTo(Tournament::class, 'torneo_id');
    }

    public function equipo1(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'equipo1_id');
    }

    public function equipo2(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'equipo2_id');
    }

    public function apuestas(): HasMany
    {
        return $this->hasMany(Apuesta::class);
    }

    public function hasStarted(): bool
    {
        return now()->gte($this->fecha_hora);
    }

    public function hasResult(): bool
    {
        return $this->equipo1_puntaje !== null && $this->equipo2_puntaje !== null;
    }

    public function recalculateApuestaPoints(): void
    {
        if (! $this->hasResult()) {
            return;
        }

        $this->apuestas->each(function (Apuesta $apuesta): void {
            $apuesta->update([
                'puntos' => Apuesta::calculatePoints(
                    $apuesta->equipo1_puntaje,
                    $apuesta->equipo2_puntaje,
                    $this->equipo1_puntaje,
                    $this->equipo2_puntaje,
                ),
            ]);
        });
    }
}
