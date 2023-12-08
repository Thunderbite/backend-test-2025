<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ApiController extends Controller
{
    public function flip()
    {
        // this is dummy example, how to interact with provided FE
        // game objective is to collect 3 same tiles, only then prize is given away
        // only then prize volume has to be counted
        // please use DATABASE layer to store and retrieve data, CACHE is just for dummy example

        $currentMove = (Cache::get(request('gameId')) ?? 0) + 1;
        Cache::put(request('gameId'), $currentMove);

        return [
            'tileImage' => asset('assets/'.mt_rand(1, 7).'.png'),
        ] + ($currentMove >= 10 ? ['message' => 'you lost'] : []);
    }
}
