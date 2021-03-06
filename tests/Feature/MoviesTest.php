<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\Turn;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MoviesTest extends TestCase {

    /**
     * @test
     * Test for: An Administrator can create Movies with at least one Turn associated to it.
     */
    public function an_administrator_can_create_movies_with_at_least_one_turn_associated_to_it()
    {
        $this->withoutExceptionHandling();
        Storage::fake('movie_files', [
            'root' => storage_path('app/public'),
            'url'  => env('APP_URL') . '/storage/movies',
        ]);
        // Given a logged-in Administrator
        $this->loginAsAdmin();
        // Given existing turns
        factory(Turn::class, 2)->create();
        // Given valid movie data
        $data = [
            'name'         => 'Batman: The Dark Knight',
            'release_date' => '14/07/2008',
            'image'        => UploadedFile::fake()->image(
                'batman-the-dark-knight-cover.jpg', 500, 500
            ),
            'turns'        => [1, 2],
        ];
        // When the request is made
        $response = $this->json('POST', 'api/movies', $data);
        // Then it should return the newly created movie
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => ['movie'],
                'message',
            ])
            ->assertJsonFragment(['name' => 'Batman: The Dark Knight']);
    }

    /**
    * @test
    * Test for: a Guest can list all the active movies.
    */
    public function a_guest_can_list_all_the_active_movies()
    {
        $this->withoutExceptionHandling();
        // Given existing movies
        factory(Movie::class, 50)->create();
        // When the request is made
        $response = $this->json('GET', 'api/movies?field=release_date&mode=asc');
        // Then it should return the active movie list
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'movies' => [
                        '*' => [
                            'name', 'release_date', 'image', 'turns',
                        ],
                    ],
                    'links',
                    'meta',
                ]
            ])
            ->assertJsonCount(10, 'data.movies');
    }

    /**
     * @test
     * Test for: An Administrator can update a movie.
     */
    public function an_administrator_can_update_a_movie()
    {
        $this->withoutExceptionHandling();
        Storage::fake('movie_files', [
            'root' => storage_path('app/public'),
            'url'  => env('APP_URL') . '/storage/movies',
        ]);
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // Given an existing movie
        $movie = factory(Movie::class)->create();
        // Given valid update data
        $data = [
            'name'  => 'Some other name',
            'image' => UploadedFile::fake()->image('other.jpg', 500, 500),
        ];
        // When the request is made
        $response = $this->json('PATCH', "api/movies/{$movie->id}", $data);
        // Then the movie is updated
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['name' => 'Some other name']);
    }

    /**
    * @test
    * Test for: An Administrator can delete a movie
    */
    public function AN_ADMINISTRATOR_CAN_DELETE_A_MOVIE()
    {
        // Given a logged-in admin
        $this->loginAsAdmin();
        // Given a movie
        $movie = factory(Movie::class)->create();
        // When the request is made
        $response = $this->json('DELETE', "/api/movies/{$movie->id}", []);
        // Then the movie is deleted
        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseMissing('movies', ['id' => $movie->id]);
    }
}
