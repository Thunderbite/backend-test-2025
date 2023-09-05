<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function flip()
    {
        $currentMove = (Cache::get(request('gameId')) ?? 0) + 1;
        Cache::put(request('gameId'), $currentMove);

        return [
            'tileImage' => asset('assets/'.mt_rand(1, 7).'.png'),
        ] + ($currentMove >= 10 ? ['message' => 'you lost'] : []);
    }
}
