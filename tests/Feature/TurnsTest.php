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
                'status' => true,
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

    /**
     * @test
     * Test for: As a Guest, a want to list turns.
     */
    public function as_a_guest_a_want_to_list_turns()
    {
        $this->withoutExceptionHandling();
        // Given several existent turns in the system
        factory(Turn::class, 50)->create();
        // When the request is made
        $response = $this->json('GET', 'api/turns');
        // Then the data should be returned and ordered by schedule.
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'turns' => [
                        '*' => [
                            'schedule',
                        ],
                    ],
                    'links',
                    'meta',
                ]
            ])
            ->assertJsonCount(10, 'data.turns');
    }

    /**
    * @test
    * Test for: an Administrator can list turns including the inactive ones.
    */
    public function an_administrator_can_list_turns_including_the_inactive_ones()
    {
        // Given several existent turns in the system
        factory(Turn::class, 50)->create();
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // When the request is made
        $response = $this->json('GET', 'api/turns');
        // Then the data should be returned and ordered by schedule.
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'turns' => [
                        '*' => [
                            'schedule', 'status',
                        ],
                    ],
                    'links',
                    'meta',
                ]
            ])
            ->assertJsonCount(10, 'data.turns');
    }

    /**
     * @test
     * Test for: An Administrator can update the details of a given turn.
     */
    public function an_administrator_can_update_the_details_of_a_given_turn()
    {
        $this->withoutExceptionHandling();
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // Given an existent turn
        $turn = factory(Turn::class)->create();
        // Given a valid data to update
        $data = ['status' => false];
        // When the request is made
        $response = $this->json('PATCH', "/api/turns/{$turn->id}", $data);
        // Then the turn should be updated
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonFragment(['status' => false]);
    }

    /**
     * @test
     * Test for: a schedule Turn cannot be updated to match another schedule Turn.
     */
    public function a_schedule_turn_cannot_be_updated_to_match_another_schedule_turn()
    {
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // Given two existent turns
        factory(Turn::class)->create(['schedule' => '15:30']);
        $turn = factory(Turn::class)->create();
        // Given a an existent turn schedule
        $data = ['schedule' => '15:30'];
        // When the request is made
        $response = $this->json('PATCH', "/api/turns/{$turn->id}", $data);
        // Then the turn should be updated
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @test
     * Test for: an Administrator can delete a turn.
     */
    public function an_administrator_can_delete_a_turn()
    {
        // Given several existent turns in the system
        $turn = factory(Turn::class)->create();
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // When the request is made
        $response = $this->json('DELETE', "api/turns/{$turn->id}", []);
        // Then the data should be returned and ordered by schedule.
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data'
            ])
            ->assertJsonFragment([
                'message' => 'The turn has been successfully deleted.',
            ]);
    }

    /**
    * @test
    * Test for: An Admin cannot delete a turn that is already deleted.
    */
    public function an_admin_cannot_delete_a_turn_that_is_already_deleted()
    {
        // Given a logged-in administrator
        $this->loginAsAdmin();
        // When the request is made
        $response = $this->json('DELETE', "api/turns/180", []);
        // Then the data should be returned and ordered by schedule.
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'message',
                'data'
            ])
            ->assertJsonFragment([
                'message' => "There isn't a match in our records for the given identifier.",
            ]);
    }
}
