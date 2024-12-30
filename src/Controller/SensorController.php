<?php

namespace App\Controller;

use App\Entity\Sensor;
use App\Service\JwtTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SensorController extends AbstractController
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

    #[Route('/api/sensor', methods: 'GET')]
    public function getSensor(Request $request): JsonResponse
    {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            );
        }

        return new JsonResponse($this->serializer->serialize($this->entity_manager->getRepository(Sensor::class)->findBy([], ['name' => 'ASC']), 'json', ['groups' => 'sensor:read']), 200, [], true);
    }

    #[Route('/api/sensor', methods: 'POST')]
    public function postSensor(Request $request): JsonResponse
    {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            );
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return new JsonResponse(['error' => 'missing data'], 500);
        }

        $sensor = new Sensor();
        $sensor->setName($data['name']);
        $this->entity_manager->persist($sensor);
        $this->entity_manager->flush();

        if (null === $sensor->getId()) {
            return new JsonResponse(['error' => 'Error inserting the Wine.'], 500);
        }

        return new JsonResponse(['message' => 'It has been inserted correctly']);
    }
}
