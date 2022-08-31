P2P wallet system
================

A platform to exchange money between users made with laravel.

## Project installation

- Clone the repository
- Install php dependencies: `composer install`
- Copy env file: `cp .env.example .env`
- Generate App key: `php artisan key:generate`
- Configure database in env file
- Run Database migration: `php artisan migrate`
- Run Seeder (will generate some test data) `php artisan db:seed`

### Third-party service setup
- Api for currency conversion from https://openexchangerates.org
    - Copy your app_id from `Open Exchange Rates` account in env file

## Contributing

Thank you for considering contributing to the project!

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
