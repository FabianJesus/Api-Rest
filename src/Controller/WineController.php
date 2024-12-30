<?php

namespace App\Controller;

use App\Entity\Wine;
use App\Service\JwtTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class WineController extends AbstractController
{
    private $entity_manager;
    private $jwtService;
    private $serializer;

    public function __construct(EntityManagerInterface $entity_manager, SerializerInterface $serializer, JwtTokenService $jwtService)
    {
        $this->entity_manager = $entity_manager;
        $this->serializer = $serializer;
        $this->jwtService = $jwtService;
    }

    #[Route('/api/wine', methods: 'GET')]
    public function getWine(Request $request): JsonResponse
    {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            );
        }

        return new JsonResponse($this->serializer->serialize($this->entity_manager->getRepository(Wine::class)->findAll(), 'json', ['groups' => 'wine:read']), 200, [], true);
    }

    #[Route('/api/wine', methods: 'POST')]
    public function postWine(Request $request): JsonResponse
    {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            );
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name']) or !isset($data['year'])) {
            return new JsonResponse(['error' => 'missing data'], 500);
        }

        $wine = new Wine();
        $wine->setName($data['name']);
        $wine->setYear($data['year']);
        $this->entity_manager->persist($wine);
        $this->entity_manager->flush();

        if (null === $wine->getId()) {
            return new JsonResponse(['error' => 'Error inserting the Wine.'], 500);
        }

        return new JsonResponse(['message' => 'It has been inserted correctly']);
    }
}
