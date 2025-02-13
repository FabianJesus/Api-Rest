<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Service\JwtTokenService;

class LoginService
{

    private JwtTokenService $jwtService;
    private UserPasswordHasherInterface $passHasher;
    private UserRepository $usuarioReposity;

    public function __construct(UserRepository $usuarioReposity,JwtTokenService $jwtService,UserPasswordHasherInterface $passHasher)
    {
        $this->jwtService = $jwtService;
        $this->passHasher = $passHasher;
        $this->usuarioReposity = $usuarioReposity;
    }

    public function login($request)
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $user = $this->usuarioReposity->findOneBy(['email' => $email]);
        if (!$user or !$this->passHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401                         
            );
        }

        $claims = [
            'email' => $email,
        ];
        
        $token = $this->jwtService->generateToken($claims, 3600);
        return new JsonResponse(['token' => $token->toString()]);
    }

}