<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movies\CreateMovieRequest;
use App\Http\Requests\Movies\UpdateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoviesController extends Controller {

    public function index(): JsonResponse
    {
        $turns = Movie::includeInactiveForAdmins(auth()->check())
            ->orderByField(request()->only('order_by', 'mode'))
            ->paginate(request('paginate') ?? 10);

        $pagination_data = $this->getPaginationInfo($turns);

        return response()->json([
            'data'    => [
                'movies' => MovieResource::collection($turns),
                'links' => $pagination_data['links'],
                'meta'  => $pagination_data['meta'],
            ],
            'message' => 'A list of all the movies in the system.',
        ]);
    }

    public function store(CreateMovieRequest $request): JsonResponse
    {
        $movie = DB::transaction(function () use ($request) {
            $data = $request->only('name', 'release_date', 'status', 'image');
            $data['image'] = Storage::disk('movie_files')->put('', $data['image']);
            $movie = Movie::create($data)->fresh();
            $movie->turns()->attach($request->get('turns'));

            return $movie;
        });

        return response()->json([
            'data'    => [
                'movie' => new MovieResource($movie->load('turns')),
            ],
            'message' => 'The movie has been successfully created.',
        ], Response::HTTP_CREATED);
    }

    public function update(UpdateMovieRequest $request, Movie $movie): JsonResponse
    {
        $movie = DB::transaction(function () use ($request, $movie) {
            $data = $request->only('name', 'release_date', 'status', 'image');
            if ($request->has('image'))
            {
                $data['image'] = Storage::disk('movie_files')->put('', $data['image']);
                $this->deleteOldImage($movie['image']);
            }
            $movie = tap($movie)->update($data)->fresh();

            if ($request->has('turns'))
            {
                $movie->turns()->sync($request->get('turns'));
            }

            return $movie;
        });

        return response()->json([
            'data'    => [
                'movie' => new MovieResource($movie->load('turns')),
            ],
            'message' => 'The movie has been successfully created.',
        ], Response::HTTP_OK);

    }

    public function destroy(Movie $movie): JsonResponse
    {
        $movie->turns()->detach();
        $movie->delete();

        return response()->json([
            'data'    => [],
            'message' => 'The movie has been successfully deleted.',
        ], Response::HTTP_OK);
    }

    private function deleteOldImage($image)
    {
        $segments = explode('/', $image);
        $name = array_pop($segments);
        Storage::disk('movie_files')->delete($name);
    }
}
