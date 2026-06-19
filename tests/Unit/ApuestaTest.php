<?php

use App\Models\Apuesta;

it('awards two points for an exact score', function () {
    expect(Apuesta::calculatePoints(2, 1, 2, 1))->toBe(2);
});

it('awards one point for correct outcome without exact score', function () {
    expect(Apuesta::calculatePoints(3, 1, 2, 0))->toBe(1);
    expect(Apuesta::calculatePoints(1, 1, 2, 2))->toBe(1);
});

it('awards zero points when the result is wrong', function () {
    expect(Apuesta::calculatePoints(2, 1, 0, 2))->toBe(0);
});
