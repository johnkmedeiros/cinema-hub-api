<?php

namespace Tests\Feature\Movies;

use App\Domain\Entities\User;
use App\Infrastructure\Persistence\Factories\UserFactory;
use App\Infrastructure\Services\Auth\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SearchMoviesTest extends TestCase
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
    public function itShouldSuccessfullySearchMovies(): void
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie*' => Http::response(
                file_get_contents(base_path('tests/mocks/movies/search-two-results.json')),
                200
            ),
        ]);

        $query = 'Lore Ipsum';
        $page = 1;

        $response = $this->json(
            'GET',
            '/api/movies/search',
            [
                'query' => $query,
                'page' => $page,
            ],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta'
            ])
            ->json();

        Http::assertSent(function ($request) use ($query, $page) {
            return str_contains($request->url(), 'https://api.themoviedb.org/3/search/movie') &&
                $request->data()['query'] === $query &&
                $request->data()['page'] === $page;
        });

        $this->assertEquals(1001, $response['data'][0]['external_id']);
        $this->assertEquals('Lore Ipsum: The Beginning', $response['data'][0]['title']);
        $this->assertEquals('In a world where text and stories are created by random generation, Lore Ipsum comes to life in an unexpected adventure of mystery and discovery.', $response['data'][0]['overview']);
        $this->assertEquals('2024-01-15', $response['data'][0]['release_date']);
        $this->assertEquals('/fakePosterPath1.jpg', $response['data'][0]['poster_path']);

        $this->assertEquals(1002, $response['data'][1]['external_id']);
        $this->assertEquals('Lore Ipsum: The Quest for Knowledge', $response['data'][1]['title']);
        $this->assertEquals('The second chapter in the Lore Ipsum series takes the protagonist on a thrilling journey to uncover the hidden truths of a digital world full of danger and intrigue.', $response['data'][1]['overview']);
        $this->assertEquals('2024-03-22', $response['data'][1]['release_date']);
        $this->assertEquals('/fakePosterPath2.jpg', $response['data'][1]['poster_path']);

        $this->assertEquals(1, $response['meta']['current_page']);
        $this->assertEquals(1, $response['meta']['total_pages']);
        $this->assertEquals(2, $response['meta']['total_results']);
    }

    #[Test]
    public function itShouldSuccessfullyGetEmptyWhenSearchMovies(): void
    {
        Http::fake([
            'https://api.themoviedb.org/3/search/movie*' => Http::response(
                file_get_contents(base_path('tests/mocks/movies/search-zero-results.json')),
                200
            ),
        ]);

        $query = 'Lore Ipsum';
        $page = 1;

        $response = $this->json(
            'GET',
            '/api/movies/search',
            [
                'query' => $query,
                'page' => $page,
            ],
            $this->headerWithToken
        )
            ->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta'
            ])
            ->json();

        Http::assertSent(function ($request) use ($query, $page) {
            return str_contains($request->url(), 'https://api.themoviedb.org/3/search/movie') &&
                $request->data()['query'] === $query &&
                $request->data()['page'] === $page;
        });

        $this->assertEquals([], $response['data']);

        $this->assertEquals(1, $response['meta']['current_page']);
        $this->assertEquals(1, $response['meta']['total_pages']);
        $this->assertEquals(0, $response['meta']['total_results']);
    }

    #[Test]
    public function itShouldNeedAuthentication(): void
    {
        $query = 'Lore Ipsum';
        $page = 1;

        $this->json(
            'GET',
            '/api/movies/search',
            [
                'query' => $query,
                'page' => $page,
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
    public function itShouldRequireQueryParameter(): void
    {
        $this->json(
            'GET',
            '/api/movies/search',
            [
                'page' => 1,
            ],
            $this->headerWithToken
        )
            ->assertStatus(422)
            ->assertJsonFragment([
                'message' => 'The query field is required.',
            ]);
    }
}
