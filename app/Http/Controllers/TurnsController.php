<?php

namespace App\Http\Controllers;

use App\Http\Requests\Turns\CreateTurnRequest;
use App\Http\Requests\Turns\UpdateTurnRequest;
use App\Http\Resources\TurnResource;
use App\Models\Turn;
use Illuminate\Http\Response;

/**
 * Class TurnsController
 *
 * @package App\Http\Controllers
 */
class TurnsController extends Controller {

    /**
     * List the turns of the system.
     *
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Store a new turn.
     *
     * @param \App\Http\Requests\Turns\CreateTurnRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Update turn resource.
     *
     * @param \App\Http\Requests\Turns\UpdateTurnRequest $request
     * @param \App\Models\Turn                           $turn
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateTurnRequest $request, Turn $turn)
    {
        $turn = tap($turn, function ($turn) use ($request) {
            $turn->update($request->validated());
        })->refresh();


        return response()->json([
            'data'    => [
                'turn' => new TurnResource($turn),
            ],
            'message' => 'The turn has been successfully updated.',
        ], Response::HTTP_OK);
    }
}
