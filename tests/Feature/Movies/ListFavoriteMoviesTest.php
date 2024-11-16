<?php

namespace Tests\Feature\Movies;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Factories\MovieFactory;
use App\Infrastructure\Persistence\Factories\UserFactory;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListFavoriteMoviesTest extends TestCase
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
    public function itShouldSuccessfullyListFavoriteMovies(): void
    {
        $externalId = 1001;
        $externalId2 = 1002;

        $persistedMovie = MovieFactory::create([
            'external_id' => $externalId,
            'title' => 'Lore Ipsum: The Beginning',
            'overview' => 'Overview text.',
            'release_date' => '2024-01-15',
            'poster_path' => '/fakePosterPath1.jpg',
        ]);

        $persistedFavorite = MovieFactory::createFavorite([
            'user_id' => $this->user->getId(),
            'movie_id' => $persistedMovie->getId(),
        ]);


        $persistedMovie2 = MovieFactory::create([
            'external_id' => $externalId2,
            'title' => 'Lore Ipsum: The Beginning 2',
            'overview' => 'Overview text 2.',
            'release_date' => '2024-01-16',
            'poster_path' => '/fakePosterPath2.jpg',
        ]);

        $persistedFavorite2 = MovieFactory::createFavorite([
            'user_id' => $this->user->getId(),
            'movie_id' => $persistedMovie2->getId(),
        ]);

        $response = $this->json(
            'GET',
            '/api/movies/favorites',
            [],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta'
            ])
            ->json();

        $this->assertEquals(1001, $response['data'][0]['external_id']);
        $this->assertEquals('Lore Ipsum: The Beginning', $response['data'][0]['title']);
        $this->assertEquals('Overview text.', $response['data'][0]['overview']);
        $this->assertEquals('2024-01-15 00:00:00', $response['data'][0]['release_date']);
        $this->assertEquals('/fakePosterPath1.jpg', $response['data'][0]['poster_path']);

        $this->assertEquals(1002, $response['data'][1]['external_id']);
        $this->assertEquals('Lore Ipsum: The Beginning 2', $response['data'][1]['title']);
        $this->assertEquals('Overview text 2.', $response['data'][1]['overview']);
        $this->assertEquals('2024-01-16 00:00:00', $response['data'][1]['release_date']);
        $this->assertEquals('/fakePosterPath2.jpg', $response['data'][1]['poster_path']);

        $this->assertEquals(1, $response['meta']['current_page']);
        $this->assertEquals(1, $response['meta']['total_pages']);
        $this->assertEquals(2, $response['meta']['total_results']);
    }


    #[Test]
    public function itShouldSuccessfullyListEmptyFavoriteMovies(): void
    {
        $response = $this->json(
            'GET',
            '/api/movies/favorites',
            [],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta'
            ])
            ->json();

        $this->assertEquals([], $response['data']);
    }

    #[Test]
    public function itShouldNeedAuthentication(): void
    {
        $this->json(
            'GET',
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
}
