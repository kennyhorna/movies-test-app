<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     *  Login a user in the system.
     *
     * @param array $attributes
     * @return mixed
     */
    public function loginAsAdmin($attributes = [])
    {
        return tap($this->createAdmin($attributes), function ($admin) {
            Passport::actingAs($admin);
        });
    }

    /**
     * Create an admin in the system.
     *
     * @param array $attributes
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed
     */
    public function createAdmin($attributes = [])
    {
        return factory(User::class)->create($attributes);
    }
}
