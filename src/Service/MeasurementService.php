<?php

namespace App\Service;
use App\Entity\Measurement;
use App\Repository\SensorRepository;
use App\Repository\WineRepository;
use App\Service\CheckUserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MeasurementService
{
    private CheckUserService $checkUserService;
    private SensorRepository $sensorRepository;
    private WineRepository $wineRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(CheckUserService $checkUserService,SensorRepository $sensorRepository,WineRepository $wineRepository,EntityManagerInterface $entityManager)
    {
        $this->checkUserService = $checkUserService;
        $this->sensorRepository = $sensorRepository;
        $this->wineRepository = $wineRepository;
        $this->entityManager = $entityManager;
    }

    public function postMeasurement($request) {
        $check = $this->checkUserService->checkUser($request);
        if($check[0]){
            return $check[1];
        }

        $data = json_decode($request->getContent(), true);
        $sensor = $this->sensorRepository->find($data['sensor']);
        $wine = $this->wineRepository->find($data['wine']);
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
        $this->entityManagerr->persist($measurement);
        $this->entityManagerr->flush();

        if (null === $measurement->getId()) {
            return new JsonResponse(['error' => 'Error inserting the Wine.'], 500);
        }

        return new JsonResponse(['message' => 'It has been inserted correctly']);
    }
}
