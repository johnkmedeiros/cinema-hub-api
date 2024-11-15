# Cinema Hub API

A simple RESTful API built with Laravel, following the **Clean Architecture** principles. Designed to interact with movie-related data, this API allows users to authenticate, search for movies, and manage their favorite movies.

The API integrates with **TheMovieDB API** to provide movie data such as search results and details about movies.

## Features

### User Authentication
- **Registration**: Create a new user account.
- **Login**: Secure user login with Laravel Sanctum, returning a token for subsequent authenticated requests.

### Cinema Hub
- **Search Movies**: Search for movies using TheMovieDB API, based on titles, genres, and other filters.
- **List Favorites**: Retrieve a list of the user’s favorite movies.
- **Favorite Movies**: Add movies to the user’s favorite list.
- **Unfavorite Movies**: Remove movies from the favorite list.

### Request Validation
- Built-in validation for incoming requests to ensure correct and consistent data.

### Authentication
- **Laravel Sanctum** is used for handling API authentication and managing user sessions. After successful login, a token is generated and used for authenticating subsequent requests.

### External Integration
- The API communicates with **TheMovieDB** for retrieving movie data. This allows users to search movies and fetch details directly from TheMovieDB's vast movie database.

### Architecture
- Follows **Clean Architecture** principles for better separation of concerns and maintainability.
  - **Domain Layer**: Business logic and core entities.
  - **Application Layer**: Use cases and application-specific rules.
  - **Infrastructure Layer**: External dependencies like database, API integrations, and authentication.


## Requirements

- PHP >= 8.2
- Composer
- Laravel >= 11.x
- MySQL or another supported database of your choice (e.g., PostgreSQL, SQLite, SQL Server)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/johnkmedeiros/cinema-hub-api.git
   ```

2. Navigate to the project directory:

   ```bash
   cd cinema-hub-api
   ```

3. Install the dependencies:

   ```bash
   composer install
   ```

4. Copy the example environment file:

   ```bash
   cp .env.example .env
   ```

5. Generate the application key:

   ```bash
   php artisan key:generate
   ```

6. Configure your database settings in the `.env` file.

## Database Configuration for Testing

If you would like to avoid affecting your development or production database during testing, you should configure a separate database for tests. Follow the steps below:

1. Open the `phpunit.xml` file in the root of your project.
2. Inside the `<php>` section, update the environment variables for the test database, as shown below:

   ```xml
   <php>
       <env name="APP_ENV" value="testing"/>
       <env name="APP_MAINTENANCE_DRIVER" value="file"/>
       <env name="BCRYPT_ROUNDS" value="4"/>
       <env name="CACHE_STORE" value="array"/>
       <env name="MAIL_MAILER" value="array"/>
       <env name="PULSE_ENABLED" value="false"/>
       <env name="QUEUE_CONNECTION" value="sync"/>
       <env name="SESSION_DRIVER" value="array"/>
       <env name="TELESCOPE_ENABLED" value="false"/>

        <!-- Uncomment and Configure your testing database here -->

        <!-- <env name="DB_CONNECTION" value="mysql"/> -->
        <!-- <env name="DB_DATABASE" value="cinema_hub_api_testing"/> -->
        <!-- <env name="DB_USERNAME" value="your_username"/> -->
        <!-- <env name="DB_PASSWORD" value="your_password"/> -->
        <!-- <env name="DB_HOST" value="127.0.0.1"/> -->
   </php>
   ```

3. Replace `cinema_hub_api_testing` with the name of your test database. Uncomment and set the `DB_USERNAME`, `DB_PASSWORD`, and `DB_HOST` lines if necessary.
4. Save the `phpunit.xml` file.

With this configuration, your tests will run against a separate database, preventing any unwanted changes to the development or production database.

## Running Migrations

Before testing the API, run the migrations to set up the database:

```bash
php artisan migrate
```

## Running Tests

To run the tests, use the following command:

```bash
vendor/bin/phpunit
```

## API Endpoints

### Authentication

- **POST** `/api/auth/register` - Register a new user
- **POST** `/api/auth/login` - Log in a user

### Movies

TODO

## API Documentation

### Authentication

#### Register User
- **Endpoint**: `POST /api/auth/register`
- **Request Body**:
  ```json
  {
    "name": "John Doe",
    "email": "john_doe@test.php",
    "password": "password",
    "password_confirmation": "password"
  }
  ```
- **Response**:
  - **201 Created**
  ```json
  {
    "access_token": "your_token_here",
    "token_type": "Bearer"
  }
  ```

#### Login User
- **Endpoint**: `POST /api/auth/login`
- **Request Body**:
  ```json
  {
    "email": "john_doe@test.php",
    "password": "password"
  }
  ```
- **Response**:
  - **200 OK**
  ```json
  {
    "access_token": "your_token_here",
    "token_type": "Bearer"
  }
  ```

### Movies
TODO
