<?php

namespace App\Service;

use App\Service\JwtTokenService;
use Symfony\Component\HttpFoundation\JsonResponse;

class CheckUserService
{
    private JwtTokenService $jwtService;

    public function __construct(JwtTokenService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    public function checkUser($request) {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return [true , new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            )];
        }
        return [false];
    }
}
