<?php

namespace App\Http\Controllers;

use App\Http\Requests\Movies\CreateMovieRequest;
use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller {

    public function store(CreateMovieRequest $request)
    {
        $movie = DB::transaction(function () use ($request) {
            $data = $request->only('name', 'release_date', 'status', 'image');
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
}
