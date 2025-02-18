<?php

declare(strict_types=1);

return [
    'maximum-game-moves-before-loss' => env('GAME_MAXIMUM_GAME_MOVES_BEFORE_LOSS', 10),
    'minimum-matching-tiles-to-win' => env('GAME_MINIUM_MATCHING_TILES_TO_WIN', 3),
];
