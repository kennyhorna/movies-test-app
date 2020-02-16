## Movie Test app

This is a simple demo app for testing purposes. You can see the user stories that support this app [here in the project](https://github.com/kennyhorna/movies-test-app/projects/1).

A live demo of this app can be found on [movies-app.kennyhorna.com](https://movies-app.kennyhorna.com).


### Setup
To get started, run the following script (inside the directory):

    composer install
    cp .env.example .env
    
For convenience I'm using by default a SQLite database. Of course, you could change the driver to use a MySQL/PostgreSQL/etc connection.
    
    touch database/database.sqlite
    php artisan migrate --seed
    
This will create an admin user with the following credentials with which you can log into to perform admin operations:

    email: kennyhorna@gmail.com
    password: My-Secure-Password

The rest of entitites are empty, but of course, you could use the configurated factories to generate data.

----


### Testing

You can run the suite of tests (located on `tests/features`) doing:

    phpunit
