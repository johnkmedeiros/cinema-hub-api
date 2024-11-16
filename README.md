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

5. Run composer dump-autoload to regenerate the autoload files to ensure that PHPUnit uses the updated configuration correctly:

```bash
composer dump-autoload
```

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

#### Authentication

- **POST** `/api/auth/register` - Register a new user
- **POST** `/api/auth/login` - Log in a user

#### Movies

- **GET** `/api/movies/search` - Search for movies based on a query and page number. (requires authentication)
- **GET** `/api/movies/favorites` - Retrieve a list of the user’s favorite movies.
- **POST** `/api/movies/favorites` - Adds a movie to the authenticated user's favorites list. The movie will be retrieved from an external API based on the provided `external_id`. (requires authentication)
- **DELETE** `/api/movies/favorites/{id}` - Remove a movie from the user's favorite list. (requires authentication)


## API Documentation

### Authentication

#### Register User
- **Endpoint**: `POST /api/auth/register`
- **Description**: Register a new user
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
      "data": {
        "access_token": "your_token_here",
        "token_type": "Bearer"
      }
    }
    ```

#### Login User
- **Endpoint**: `POST /api/auth/login`
- **Description**: Log in a user
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
      "data": {
        "access_token": "your_token_here",
        "token_type": "Bearer"
      }
    }
    ```
### Movies

#### Search Movies
- **Endpoint**: `GET /api/movies/search`
- **Description**: Search for movies based on a query and page number. 
- **Headers**:
  ```
  Authorization: Bearer your_token_here
  ```
- **Query Parameters**:
  - `query` (string): The search term for the movie (required).
  - `page` (integer): The page number for pagination (optional).

- **Request Example**:
  ```bash
  GET /api/movies/search?query=Lore+Ipsum&page=1
  ```

- **Response**:
  - **200 OK**
    ```json
    {
      "data": [
        {
          "external_id": 1001,
          "title": "Lore Ipsum: The Beginning",
          "overview": "In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.",
          "release_date": "2024-01-15",
          "poster_path": "/fakePosterPath1.jpg"
        },
        {
          "external_id": 1002,
          "title": "Lore Ipsum: The Quest for Knowledge",
          "overview": "The second chapter in the Lore Ipsum series takes the protagonist on a thrilling journey to uncover the hidden truths of a digital world full of danger and intrigue.",
          "release_date": "2024-03-22",
          "poster_path": "/fakePosterPath2.jpg"
        }
      ],
      "meta": {
        "current_page": 1,
        "total_pages": 1,
        "total_results": 2
      }
    }
    ```

- **Error Responses**:
  - **422 Unprocessable Content** - Missing required parameters or invalid query:
    ```json
    {
      "message": "The query parameter is required."
    }
    ```
  - **401 Unauthorized** - If authentication token is invalid or not provided:
    ```json
    {
      "message": "Unauthenticated."
    }
    ```    
#### List favorites
- **Endpoint**: `GET /api/movies/favorites`
- **Description**: Retrieve a list of the user’s favorite movies.
- **Headers**:
  ```
  Authorization: Bearer your_token_here
  ```
- **Query Parameters**:
  - `page` (integer): The page number for pagination (optional).

- **Request Example**:
  ```bash
  GET /api/movies/favorites?page=1
  ```

- **Response**:
  - **200 OK**
    ```json
    {
      "data": [
        {
          "external_id": 1001,
          "title": "Lore Ipsum: The Beginning",
          "overview": "In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.",
          "release_date": "2024-01-15",
          "poster_path": "/fakePosterPath1.jpg"
        },
        {
          "external_id": 1002,
          "title": "Lore Ipsum: The Quest for Knowledge",
          "overview": "The second chapter in the Lore Ipsum series takes the protagonist on a thrilling journey to uncover the hidden truths of a digital world full of danger and intrigue.",
          "release_date": "2024-03-22",
          "poster_path": "/fakePosterPath2.jpg"
        }
      ],
      "meta": {
        "current_page": 1,
        "total_pages": 1,
        "total_results": 2
      }
    }
    ```

- **Error Responses**:
  - **401 Unauthorized** - If authentication token is invalid or not provided:
    ```json
    {
      "message": "Unauthenticated."
    }
    ```
#### Add Movie to Favorites
- **Endpoint**: `POST /api/movies/favorites`
- **Description**: Add a movie to the user's favorite list.
- **Headers**:
  ```bash
  Authorization: Bearer your_token_here
  ```
- **Request Body**:
  ```json
  {
    "external_id": 1001
  }
  ```

- **Response**:
  - **200 OK**
    ```json
    {
      "message": "Movie #1001 added to your favorite list."
    }
    ```

- **Error Responses**:
  - **422 Unprocessable Content** - Missing `external_id` or invalid value:
    ```json
    {
      "message": "The themoviedb id field is required."
    }
    ```
  - **422 Unprocessable Content** - If the movie is already in the user's favorite list:
    ```json
    {
      "success": false,
      "message": "Movie is already favorited.",
      "error_code": "MOVIE_ALREADY_FAVORITED"
    }
    ```
  - **401 Unauthorized** - If authentication token is invalid or not provided:
    ```json
    {
      "message": "Unauthenticated."
    }
    ```
  - **404 Not Found** - If the movie is not found on the external API:
    ```json
    {
      "success": false,
      "message": "Movie not found.",
      "error_code": "MOVIE_NOT_FOUND",
    }
    ```    
#### Remove Movie from Favorites
- **Endpoint**: `DELETE /api/movies/favorites/{external_id}`
- **Description**: Remove a movie from the user's favorite list.
- **Headers**:
  ```bash
  Authorization: Bearer your_token_here
  ```
- **URL Parameters**:
  - `external_id`: The external ID of the movie to be removed from favorites.

- **Response**:
  - **200 OK**
    ```json
    {
      "message": "Movie #1001 removed from your favorite list."
    }
    ```

- **Error Responses**:
  - **422 Unprocessable Content** - If the movie is not in the user's favorite list:
    ```json
    {
      "success": false,
      "message": "Movie is not favorited.",
      "error_code": "MOVIE_NOT_FAVORITED"
    }
    ```
  - **401 Unauthorized** - If authentication token is invalid or not provided:
    ```json
    {
      "message": "Unauthenticated."
    }
    ```
