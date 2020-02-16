<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Client;
use Tests\TestCase;

class AdminCreationThroughTerminalTest extends TestCase {

    use DatabaseMigrations;

    /**
    * @test
    * Test for: a User can be created through the terminal.
    */
    public function a_user_can_be_created_through_the_terminal()
    {
        $this->artisan('create:admin')
            ->expectsQuestion('What is the admin name?', 'Alexandre Lacazette')
            ->expectsQuestion('What is his/her email address?', 'lacazette@arsenal.com')
            ->expectsQuestion('Please, introduce the password', 'A-really-Secure-passworD')
            ->expectsOutput('The admin has been successfully created.');
    }
}
