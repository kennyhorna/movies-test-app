<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Kenny Horna',
            'email' => 'kennyhorna@gmail.com',
            'password' => 'My-Secure-Password',
        ]);
    }
}
