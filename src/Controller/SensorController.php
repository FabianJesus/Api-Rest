<?php

namespace App\Controller;

use App\Service\SensorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class SensorController extends AbstractController
{
    private $sensorService;

    public function __construct(SensorService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    #[Route('/api/sensor', methods: 'GET')]
    public function getSensor(Request $request)
    {
        return $this->sensorService->getSensor($request);
    }

    #[Route('/api/sensor', methods: 'POST')]
    public function postSensor(Request $request)
    {
        return $this->sensorService->postSensor($request);
    }
}
