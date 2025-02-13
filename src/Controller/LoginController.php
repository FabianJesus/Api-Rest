<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\LoginService;

class LoginController extends AbstractController
{

    private LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    #[Route('/api/login', methods:"POST")]
    public function login(Request $request)
    {
        return $this->loginService->login($request);
    }

}