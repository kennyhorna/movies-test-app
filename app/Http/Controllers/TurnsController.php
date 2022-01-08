<?php

namespace App\Http\Controllers;

use App\Http\Requests\Turns\CreateTurnRequest;
use App\Http\Requests\Turns\UpdateTurnRequest;
use App\Http\Resources\TurnResource;
use App\Models\Turn;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

/**
 * Class TurnsController
 *
 * @package App\Http\Controllers
 */
class TurnsController extends Controller {

    public function index(): JsonResponse
    {
        $turns = Turn::includeInactiveForAdmins(auth()->check())
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

    public function store(CreateTurnRequest $request): JsonResponse
    {
        $turn = Turn::create($request->validated())->refresh();

        return response()->json([
            'data'    => [
                'turn' => new TurnResource($turn),
            ],
            'message' => 'The turn has been successfully created',
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateTurnRequest $request, Turn $turn): JsonResponse
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

    public function destroy(Turn $turn): JsonResponse
    {
        $turn->movies()->detach();
        $turn->delete();

        return response()->json([
            'data'    => [],
            'message' => 'The turn has been successfully deleted.',
        ], Response::HTTP_OK);
    }
}
