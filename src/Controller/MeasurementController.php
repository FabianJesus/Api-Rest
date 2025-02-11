<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MeasurementService;

class MeasurementController extends AbstractController
{
    private MeasurementService $measurementService;

    public function __construct(MeasurementService $measurementService)
    {
        $this->measurementService = $measurementService;
    }

    #[Route('/api/measurement', methods: 'POST')]
    public function postMeasurement(Request $request)
    {
        return $this->measurementService->getMeasurementInfo($request);
    }
}
