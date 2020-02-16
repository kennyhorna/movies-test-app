<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Tests\TestCase;

class AuthenticationTest extends TestCase {

    use DatabaseMigrations;

    /**
     * @test
     * Test for: a Administrator can log-in into the system with correct credentials
     */
    public function a_administrator_can_log_in_into_the_system_with_correct_credentials()
    {
        // Given an existent admin in the system database
        $oauth_client = Client::findOrFail(2);
        $admin = $this->createAdmin($credential = [
            'name'     => 'Mesut Özil',
            'email'    => $email = 'ozil@arsenal.com',
            'password' => $password = 'My-$uPer-seCr3t-Pas$word'
        ]);

        // When the request is made with correct credentials
        $data = [
            'grant_type'    => 'password',
            'client_id'     => $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'username'      => $email,
            'password'      => $password,
            'scope'         => '',
        ];

        $response = $this->json('POST', 'oauth/token', $data);

        // Then it should return his/her access token
        $response
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'token_type', 'expires_in', 'access_token', 'refresh_token',
            ]);
    }

    /**
    * @test
    * Test for: a User cannot login with incorrect credentials.
    */
    public function a_user_cannot_login_with_incorrect_credentials()
    {
        // Given an existent admin in the system database
        $oauth_client = Client::findOrFail(2);
        $admin = $this->createAdmin($credential = [
            'name'     => 'Mesut Özil',
            'email'    => $email = 'ozil@arsenal.com',
            'password' => $password = 'My-$uPer-seCr3t-Pas$word'
        ]);

        // When the request is made with incorrect credentials
        $data = [
            'grant_type'    => 'password',
            'client_id'     => $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'username'      => $email,
            'password'      => 'some-wrong-password',
            'scope'         => '',
        ];

        $response = $this->json('POST', 'oauth/token', $data);

        // Then it should return his/her access token
        $response
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertJsonStructure([
                'error', 'error_description', 'hint', 'message',
            ]);
    }

    protected function setUp() : void
    {
        parent::setUp();
        Artisan::call('migrate', ['-vvv' => true]);
        Artisan::call('passport:install', ['-vvv' => true]);
        Artisan::call('db:seed', ['-vvv' => true]);
    }
}
