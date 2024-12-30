<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class SensorTest extends TestCase
{
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = getenv('SECURITY_TOKEN');
    }

    public function testSensorGet(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/api/sensor', [
            'headers' => [
                'token' => $this->token,
            ],
        ]);

        self::assertEquals(200, $response->getStatusCode());
    }

    public function testSensorGetError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'http://localhost:8000/api/sensor', [
            'headers' => [
                'token' => 'IUzI1NiJ9.eyJpc3MiOiJAcGb0ZnqkKY',
            ],
        ]);
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testSensorPost(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/sensor', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
                'name' => 'Pepe',
            ],
        ]);

        self::assertEquals(200, $response->getStatusCode());
        $content = $response->toArray();
        self::assertArrayHasKey('message', $content, '"message" missing in the answer');
    }

    public function testSensorPostError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/sensor', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
            ],
        ]);

        self::assertEquals(500, $response->getStatusCode());

        $response = $client->request('POST', 'http://localhost:8000/api/sensor', [
            'headers' => [
                'token' => '',
            ],
            'json' => [
                'name' => 'Pepe',
            ],
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }
}
