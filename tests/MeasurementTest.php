<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class MeasurementTest extends TestCase
{
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->token = getenv('SECURITY_TOKEN');
    }

    public function testMeasurementPost(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/measurement', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
                'year' => 2015,
                'sensor' => 1,
                'wine' => 1,
                'color' => 'rosado',
                'temperature' => '15ª',
                'graduation' => '25º',
                'ph' => 45,
            ],
        ]);
        self::assertEquals(200, $response->getStatusCode());
        $content = $response->toArray();
        self::assertArrayHasKey('message', $content, '"message" missing in the answer');
    }

    public function testMeasurementPostError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/measurement', [
            'headers' => [
                'token' => $this->token,
            ],
            'json' => [
            ],
        ]);

        self::assertEquals(500, $response->getStatusCode());

        $response = $client->request('POST', 'http://localhost:8000/api/measurement', [
            'headers' => [
                'token' => '',
            ],
            'json' => [
                'year' => 2015,
                'sensor' => 1,
                'wine' => 1,
                'color' => 'rosado',
                'temperature' => '15ª',
                'graduation' => '25º',
                'ph' => 45,
            ],
        ]);

        self::assertEquals(401, $response->getStatusCode());
    }
}
