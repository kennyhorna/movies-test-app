<?php

namespace Tests\Feature;

use App\Models\Turn;
use Illuminate\Http\Response;
use Tests\TestCase;

class TurnsTest extends TestCase {

    /**
     * @test
     * Test for: an Admin can create a turn in the system with correct data.
     */
    public function an_admin_can_create_a_turn_in_the_system_with_correct_data()
    {
        // Given a logged-in admin
        $this->loginAsAdmin();
        // Given valid data to create a turn
        $data = ['schedule' => '15:30'];
        // When the request is made
        $response = $this->json('POST', 'api/turns', $data);
        // Then the resource is created
        $response
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'message',
                'data' => ['turn']
            ])
            ->assertJsonFragment([
                'schedule' => '15:30',
            ]);
    }

    /**
    * @test
    * Test for: Two turns cannot be created with the same schedule.
    */
    public function two_turns_cannot_be_created_with_the_same_schedule()
    {
        // Given an existent turn
        factory(Turn::class)->create(['schedule' => '20:00']);
        // Given a logged-in admin
        $this->loginAsAdmin();
        // Given data to create a turn with the same schedule as an existent one
        $data = ['schedule' => '20:00'];
        // When the request is made
        $response = $this->json('POST', 'api/turns', $data);
        // Then the request must return an error
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }

    /**
    * @test
    * Test for: An Admin cannot create a turn with invalid data.
    */
    public function an_admin_cannot_create_a_turn_with_invalid_data_()
    {
        // Given a logged-in admin
        $this->loginAsAdmin();
        // Given valid data to create a turn
        $data = ['schedule' => '55:30'];
        // When the request is made
        $response = $this->json('POST', 'api/turns', $data);
        // Then the resource is created
        $response
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonStructure(['message']);
    }
}
