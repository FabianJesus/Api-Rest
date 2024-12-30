<?php

namespace App\Controller;

use App\Entity\Measurement;
use App\Entity\Sensor;
use App\Entity\Wine;
use App\Service\JwtTokenService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class MeasurementController extends AbstractController
{
    private $entity_manager;
    private $jwtService;

    public function __construct(EntityManagerInterface $entity_manager, JwtTokenService $jwtService)
    {
        $this->entity_manager = $entity_manager;
        $this->jwtService = $jwtService;
    }

    #[Route('/api/measurement', methods: 'POST')]
    public function postMeasurement(Request $request): JsonResponse
    {
        if (empty($request->headers->get('token')) or !$this->jwtService->validateToken($request->headers->get('token'))) {
            return new JsonResponse(
                ['request_denied' => 'Unauthorized'],
                401
            );
        }

        $data = json_decode($request->getContent(), true);
        $sensor = $this->entity_manager->getRepository(Sensor::class)->find($data['sensor']);
        $wine = $this->entity_manager->getRepository(Wine::class)->find($data['wine']);
        if (!isset($data['sensor']) or !isset($data['year']) or !isset($data['wine']) or !isset($data['color']) or !isset($data['temperature']) or !isset($data['graduation']) or !isset($data['ph']) or null === $wine or null === $sensor) {
            return new JsonResponse(['error' => 'missing data'], 500);
        }

        $measurement = new Measurement();
        $measurement->setYear($data['year']);
        $measurement->setSensor($sensor);
        $measurement->setWine($wine);
        $measurement->setColor($data['color']);
        $measurement->setTemperature($data['temperature']);
        $measurement->setGraduation($data['graduation']);
        $measurement->setPh($data['ph']);
        $this->entity_manager->persist($measurement);
        $this->entity_manager->flush();

        if (null === $measurement->getId()) {
            return new JsonResponse(['error' => 'Error inserting the Wine.'], 500);
        }

        return new JsonResponse(['message' => 'It has been inserted correctly']);
    }
}
