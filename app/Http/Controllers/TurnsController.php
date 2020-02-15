<?php

namespace App\Http\Controllers;

use App\Http\Requests\Turns\CreateTurnRequest;
use App\Http\Resources\TurnResource;
use App\Models\Turn;
use Illuminate\Http\Response;

class TurnsController extends Controller {

    public function store(CreateTurnRequest $request)
    {
        $turn = Turn::create($request->validated());

        return response()->json([
            'data'    => [
                'turn' => new TurnResource($turn),
            ],
            'message' => 'The turn has been successfully created',
        ], Response::HTTP_CREATED);
    }
}
