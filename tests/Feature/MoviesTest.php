<?php

namespace Tests\Feature;

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
        $response = $this->json('POST', 'api/movies', $data); dd($response->json());
        // Then it should return the newly created movie
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'data' => ['movie'],
                'message',
            ])
            ->assertJsonFragment(['name' => 'Batman: The Dark Knight']);
    }

}
