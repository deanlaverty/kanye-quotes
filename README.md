# Kanye Quotes API

## Setup
The project is setup using Laravel sail which uses Docker under the hood so you will first need to install
Docker on your system.

Once you have pulled the project down you can run `./vendor/bin/sail up` you can append the `-d` flag
to boot the containers in detached mode if you wish.

## ENV
The API URL & Cache TTL (in seconds) is defined in config, but this can be overridden in the .env file.

## Endpoints
Both endpoints are protected via an API key. You can access the below endpoints using the following Authorization header: `X-Api-Key`.

This is defined in config and can be set within the .env file

#### Get kanye quotes
```
GET http://localhost/api/quotes/kanye-west
```

This will get 5 random quotes and cache them for 2 minutes.

#### Refresh kanye quotes
```
GET http://localhost/api/quotes/refresh/kanye-west
```

This will refresh the quotes to return 5 new quotes and then cache them again.

## Tests
You can run tests using the sail command `./vendor/bin/sail artisan test`

## Laravel Pint
Laravel pint is a wrapper around CS fixer and ensures the code style is adhered to. I am currently using Laravel's
default code style configuration. 

This can be checked with the following command: `./vendor/bin/sail pint`

## CI
CI is handled via a Github action. On push it will setup the application, run Laravel pint and then run our tests.
The workflow can be found in `.github/workflows`
