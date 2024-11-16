<?php

namespace Tests\Feature\Movies;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Factories\MovieFactory;
use App\Infrastructure\Persistence\Factories\UserFactory;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AddMovieToFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private AuthService $authService;
    private string $token;
    private array $headerWithToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::create([
            'name' => 'John Doe',
        ]);

        $this->authService = new AuthService();

        $this->token = $this->authService->generateToken($this->user);
        $this->headerWithToken = [
            'Authorization' => 'Bearer ' . $this->token,
        ];
    }

    #[Test]
    public function itShouldSuccessfullyFavoriteMovie(): void
    {
        $externalId = 1001;

        Http::fake([
            "https://api.themoviedb.org/3/movie/{$externalId}*" => Http::response(
                file_get_contents(base_path('tests/mocks/movies/show-success.json')),
                200
            ),
        ]);

        $this->json(
            'POST',
            '/api/movies/favorites',
            [
                'external_id' => $externalId,
            ],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => "All right, movie #{$externalId} is in your favorites list.",
            ])
            ->json();

        $persistedMovie = DB::table('movies')->where('external_id', $externalId)->first();

        $this->assertEquals($externalId, $persistedMovie->external_id);
        $this->assertEquals('Lore Ipsum: The Beginning', $persistedMovie->title);
        $this->assertEquals('In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.', $persistedMovie->overview);
        $this->assertEquals('2024-01-15', $persistedMovie->release_date);
        $this->assertEquals('/fakePosterPath1.jpg', $persistedMovie->poster_path);

        $this->assertDatabaseHas('user_favorite_movies', [
            'user_id' => $this->user->getId(),
            'movie_id' => $persistedMovie->id,
        ]);
    }

    #[Test]
    public function itShouldSuccessfullyFavoriteMovieEvenIfTheMovieIsAlreadyPersistedBefore(): void
    {
        $externalId = 1001;

        $persistedMovie = MovieFactory::create([
            'external_id' => $externalId,
            'title' => 'Lore Ipsum: The Beginning',
            'overview' => 'In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.',
            'release_date' => '2024-01-15',
            'poster_path' => '/fakePosterPath1.jpg',
        ]);

        Http::fake([
            "https://api.themoviedb.org/3/movie/{$externalId}*" => Http::response(
                file_get_contents(base_path('tests/mocks/movies/show-success.json')),
                200
            ),
        ]);

        $response = $this->json(
            'POST',
            '/api/movies/favorites',
            [
                'external_id' => $externalId,
            ],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => "All right, movie #{$externalId} is in your favorites list.",
            ])
            ->json();

        $this->assertEquals($externalId, $persistedMovie->getExternalId());
        $this->assertEquals('Lore Ipsum: The Beginning', $persistedMovie->getTitle());
        $this->assertEquals('In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.', $persistedMovie->getOverview());
        $this->assertEquals('2024-01-15 00:00:00', $persistedMovie->getReleaseDate());
        $this->assertEquals('/fakePosterPath1.jpg', $persistedMovie->getPosterPath());
    }

    #[Test]
    public function itShouldNotFoundWhenShowMovie(): void
    {
        $externalId = 1001;

        Http::fake([
            "https://api.themoviedb.org/3/movie/{$externalId}*" => Http::response(
                file_get_contents(base_path('tests/mocks/movies/show-not-found.json')),
                404
            ),
        ]);

        $this->json(
            'POST',
            '/api/movies/favorites',
            [
                'external_id' => $externalId,
            ],
            $this->headerWithToken
        )
            ->assertStatus(404)
            ->assertJsonFragment([
                'success' => false,
                'message' => 'Movie not found.',
                'error_code' => 'MOVIE_NOT_FOUND',
            ])
            ->json();
    }


    #[Test]
    public function itShouldNotFavoriteMovieIfAlreadyFavorited(): void
    {
        $externalId = 1001;

        $persistedMovie = MovieFactory::create([
            'external_id' => $externalId,
            'title' => 'Lore Ipsum: The Beginning',
            'overview' => 'In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.',
            'release_date' => '2024-01-15',
            'poster_path' => '/fakePosterPath1.jpg',
        ]);

        $persistedFavorite = MovieFactory::createFavorite([
            'user_id' => $this->user->getId(),
            'movie_id' => $persistedMovie->getId(),
        ]);

        Http::fake([
            "https://api.themoviedb.org/3/movie/{$externalId}*" => Http::response(
                file_get_contents(base_path('tests/mocks/movies/show-success.json')),
                200
            ),
        ]);

        $response = $this->json(
            'POST',
            '/api/movies/favorites',
            [
                'external_id' => $externalId,
            ],
            $this->headerWithToken
        )
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
                'message' => "Movie is already favorited.",
                'error_code' => 'MOVIE_ALREADY_FAVORITED',
            ])
            ->json();
    }

    #[Test]
    public function itShouldNeedAuthentication(): void
    {
        $this->json(
            'POST',
            '/api/movies/favorites',
            [
                'external_id' => 1000,
            ],
            [
                'Authorization' => 'Bearer wrongtoken'
            ]
        )
            ->assertStatus(401)
            ->assertJsonFragment([
                'message' => 'Unauthenticated.',
            ])
            ->json();
    }

    #[Test]
    public function itShouldRequireExternalIdParameter(): void
    {
        $this->json(
            'POST',
            '/api/movies/favorites',
            [],
            $this->headerWithToken
        )
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'The external id field is required.',
            ]);
    }
}
