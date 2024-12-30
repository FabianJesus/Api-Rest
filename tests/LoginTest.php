<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class LoginTest extends TestCase
{
    public function testLoginError(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/login');
        self::assertEquals(401, $response->getStatusCode());
    }

    public function testLogin(): void
    {
        $client = HttpClient::create();
        $response = $client->request('POST', 'http://localhost:8000/api/login', [
            'json' => [
                'email' => 'pruebas@api.com',
                'password' => 'mypassword123',
            ],
        ]);
        self::assertEquals(200, $response->getStatusCode());
        $content = $response->toArray();
        self::assertArrayHasKey('token', $content, '"token" missing in the answer');
    }
}
