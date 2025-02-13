<?php

namespace App\Service;

use App\Entity\Sensor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use App\Repository\SensorRepository;

class SensorService
{
    private CheckUserService $checkUserService;
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;
    private SensorRepository $sensorRepository;

    public function __construct(CheckUserService $checkUserService, EntityManagerInterface $entityManager, SerializerInterface $serializer, SensorRepository $sensorRepository)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->checkUserService = $checkUserService;
        $this->sensorRepository = $sensorRepository;
    }

    public function getSensor($request)
    {
        $check = $this->checkUserService->checkUser($request);
        if($check[0]){
            return $check[1];
        }
        return new JsonResponse($this->serializer->serialize($this->sensorRepository->findBy([], ['name' => 'ASC']), 'json', ['groups' => 'sensor:read']), 200, [], true);
    }

    public function postSensor($request)
    {
        $check = $this->checkUserService->checkUser($request);
        if($check[0]){
            return $check[1];
        }

        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return new JsonResponse(['error' => 'missing data'], 500);
        }

        $sensor = new Sensor();
        $sensor->setName($data['name']);
        $this->entityManager->persist($sensor);
        $this->entityManager->flush();

        if (null === $sensor->getId()) {
            return new JsonResponse(['error' => 'Error inserting the Wine.'], 500);
        }

        return new JsonResponse(['message' => 'It has been inserted correctly']);
    }
}
