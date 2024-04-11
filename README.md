# 🚀 Objective Challenge

## Environment Setup

### 🛠️ Database Configuration

Before running the project, it's necessary to configure the environment by copying the `.env` and `.env.testing` files based on the `.env.example` file and adjusting the database information.

1. **.env**:

    - 📝 Copy the `.env.example` file to `.env`.
    - 🛠️ Add the database information to the `.env` file.

2. **.env.testing**:
    - 📝 Copy the `.env.example` file to `.env.testing`.
    - 🔄 Change `APP_ENV` to `testing`.
    - 🛠️ Add the database information for the testing environment.

## Installation and Execution

To install dependencies and set up the environment, follow these steps:

1. **Docker**:

    - 🐳 Run the command `docker compose up -d`.
    - 🚀 Then run `docker exec -it objective-challenge-laravel.test-1 php artisan migrate` to execute the migrations.
    - ⚙️ To run migrations in the testing database, use `docker exec -it objective-challenge-laravel.test-1 php artisan migrate --env=testing`.

2. **Laravel Sail**:
    - The project was built using Laravel Sail, which allows for execution using it as well ([Sail Documentation](https://laravel.com/docs/11.x/sail)).
    - 🐳 Run the command `./vendor/bin/sail up -d`.
    - 🚀 Then run `./vendor/bin/sail up -d php artisan migrate` to execute the migrations.
    - ⚙️ To run migrations in the testing database, use `./vendor/bin/sail up -d php artisan migrate --env=testing`.

ℹ️ **Note:** Depending on your setup, you may also need to run `composer install` and `composer update` before executing the migration commands.

## Running Tests

1. **Docker**:

    - 🧪 Run the command `docker exec -it objective-challenge-laravel.test-1 php artisan test`.
    - 🧪 Run the command `docker exec -it objective-challenge-laravel.test-1 ./vendor/bin/phpunit --coverage-html coverage` to check a coverage.

2. **Laravel Sail**:
    - 🧪 Run the command `./vendor/bin/sail php artisan test`.
    - 🧪 Run the command `./vendor/bin/sail ./vendor/bin/phpunit --coverage-html coverage` to check a coverage.
