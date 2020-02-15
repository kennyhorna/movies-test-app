<?php

namespace App\Http\Controllers;

use App\Http\Requests\Turns\CreateTurnRequest;
use App\Http\Resources\TurnResource;
use App\Models\Turn;
use Illuminate\Http\Response;

class TurnsController extends Controller {

    public function index()
    {
        $turns = Turn::query()
            ->includeInactiveForAdmins(auth()->check())
            ->orderBy('schedule')
            ->paginate(request('paginate') ?? 10);

        $pagination_data = $this->getPaginationInfo($turns);

        return response()->json([
            'data'    => [
                'turns' => TurnResource::collection($turns),
                'links' => $pagination_data['links'],
                'meta'  => $pagination_data['meta'],
            ],
            'message' => 'A list of all the turns in the system.',
        ]);
    }

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
