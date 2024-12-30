<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class WineTest extends TestCase
{
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = getenv('SECURITY_TOKEN');
    }

    public function testWineGet(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/api/wine', [
            'headers' => [
                'token' => $this->token,
            ],
        ]);

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testWineGetError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/api/wine', [
            'headers' => [
                'token' => 'IUzI1NiJ9.eyJpc3MiOiJAcGb0ZnqkKY',
            ],
        ]);
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testWinePost(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/wine', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
                'name' => 'Rioja',
                'year' => 2012,
            ],
        ]);

        self::assertEquals(200, $response->getStatusCode());
        $content = $response->toArray();
        self::assertArrayHasKey('message', $content, '"message" missing in the answer');
    }

    public function testWinePostError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/wine', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
                'year' => 2012,
            ],
        ]);

        self::assertEquals(500, $response->getStatusCode());

        $response = $client->request('POST', 'http://localhost:8000/api/wine', [
            'headers' => [
                'token' => '',
            ],
            'json' => [
                'name' => 'Rioja',
                'year' => 2012,
            ],
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }
}
