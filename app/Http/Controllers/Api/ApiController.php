<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function flip()
    {
        /**
         * This is a simplified example to demonstrate interaction with the provided frontend (FE).
         * The game objective is to collect three matching tiles to win a prize. Once three matching tiles are collected:
         *   - The game ends.
         *   - The prize is awarded, and its daily volume limit (defined in the back office) must be updated.
         *
         * Requirements:
         * - Use the database layer to store and manage all game-related data, including game state and prize counts.
         * - Cache is used here only for demonstration purposes and should be replaced with proper database storage.
         */

        // Simulate the current move count using cache (replace with database in production).
        $currentMove = (Cache::get(request('gameId')) ?? 0) + 1;
        Cache::put(request('gameId'), $currentMove);


        if ($currentMove >= 10) {
            Cache::forget(request('gameId'));
        }

        // Return the next tile and a loss message if the move limit is exceeded.
        return [
            'tileImage' => asset('assets/'.random_int(1, 7).'.png'),
        ] + ($currentMove >= 10 ? ['message' => 'You lost!'] : []);
    }
}
