<?php

namespace Tests\Feature\Movies;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Factories\MovieFactory;
use App\Infrastructure\Persistence\Factories\UserFactory;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RemoveMovieFromFavoritesTest extends TestCase
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
    public function itShouldSuccessfullyRemoveFavoriteMovie(): void
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

        $this->json(
            'DELETE',
            "/api/movies/favorites/{$externalId}",
            [],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonFragment([
                'message' => "Movie #{$externalId} removed from your favorite list.",
            ])
            ->json();

        $this->assertDatabaseMissing('user_favorite_movies', [
            'user_id' => $this->user->getId(),
            'movie_id' => $persistedMovie->getId(),
        ]);
    }

    #[Test]
    public function itShouldFailIfMovieIsNotFavorited(): void
    {
        $externalId = 1001;

        $this->json(
            'DELETE',
            "/api/movies/favorites/{$externalId}",
            [],
            $this->headerWithToken
        )
            ->assertStatus(422)
            ->assertJsonFragment([
                'success' => false,
                'message' => 'Movie is not favorited.',
                'error_code' => 'MOVIE_NOT_FAVORITED'
            ])
            ->json();
    }

    #[Test]
    public function itShouldNeedAuthentication(): void
    {
        $externalId = 1001;

        $this->json(
            'DELETE',
            "/api/movies/favorites/{$externalId}",
            [],
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
}
