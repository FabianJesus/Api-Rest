<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\JwtTokenService;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class LoginController extends AbstractController
{

    private $entity_manager;
    private $jwtService;
    private $pass_hasher;

    public function __construct(EntityManagerInterface $entity_manager,JwtTokenService $jwtService,UserPasswordHasherInterface $pass_hasher)
    {
        $this->entity_manager = $entity_manager;
        $this->jwtService = $jwtService;
        $this->pass_hasher = $pass_hasher;
    }

    #[Route('/api/login', methods:"POST")]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $user = $this->entity_manager->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user or !$this->pass_hasher->isPasswordValid($user, $password)) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401                         
            );
        }

        $claims = [
            'email' => $email,
        ];
        
        $token = $this->jwtService->generateToken($claims, 3600);

        $tokenString = $token->toString();
        return new JsonResponse(['token' => $token->toString()]);

        return new JsonResponse(
            ['request_denied' => 'Unauthorized'],
            401                         
        );
    }

}