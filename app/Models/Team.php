<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'flag', 'tournament_id'])]
class Team extends Model
{
    use HasFactory;

    public function tournament(): BelongsTo
    {
        return $this->belongsTo(Tournament::class);
    }

    public function homeMatches(): HasMany
    {
        return $this->hasMany(Partido::class, 'equipo1_id');
    }

    public function awayMatches(): HasMany
    {
        return $this->hasMany(Partido::class, 'equipo2_id');
    }
}
