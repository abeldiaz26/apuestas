<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'partido_id',
    'user_id',
    'equipo1_puntaje',
    'equipo2_puntaje',
    'puntos',
])]
class Apuesta extends Model
{
    use HasFactory;

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isEditable(): bool
    {
        return $this->partido ? now()->lt($this->partido->fecha_hora) : false;
    }

    public static function calculatePoints(int $predicted1, int $predicted2, ?int $actual1, ?int $actual2): int
    {
        if ($actual1 === null || $actual2 === null) {
            return 0;
        }

        if ($predicted1 === $actual1 && $predicted2 === $actual2) {
            return 2;
        }

        return self::matchOutcome($predicted1, $predicted2) === self::matchOutcome($actual1, $actual2) ? 1 : 0;
    }

    private static function matchOutcome(int $score1, int $score2): string
    {
        if ($score1 === $score2) {
            return 'draw';
        }

        return $score1 > $score2 ? 'home' : 'away';
    }
}
