<?php

namespace App\Http\Controllers\Api;

use App\Actions\Api\FlipTileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\FlipRequest;

class ApiController extends Controller
{
    public function flip(FlipRequest $request, FlipTileAction $action)
    {
        return $action->handle($request->integer('gameId'), $request->integer('tileIndex'));
    }
}
