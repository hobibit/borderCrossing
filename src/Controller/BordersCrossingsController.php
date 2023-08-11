<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\BordersCrossings\BordersCrossingsService;
use App\Service\BordersCrossings\CountriesJsonNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class BordersCrossingsController extends AbstractController
{
    #[Route('/routing/{origin}/{destination}', name: 'app_borders_crossings')]
    public function route(string $origin, string $destination, BordersCrossingsService $bordersCrossingsService, CountriesJsonNormalizer $normailzer): JsonResponse
    {
        $countriesJson = file_get_contents('https://raw.githubusercontent.com/mledoze/countries/master/countries.json');

        $countries = $normailzer->normnalize($countriesJson);
        $borderCrossingRoute = $bordersCrossingsService->getBorderCrossingRoute($origin, $destination, $countries);

        $status = Response::HTTP_OK;
        if (count($borderCrossingRoute) === 0) {
            $status = Response::HTTP_BAD_REQUEST;
        }

        return $this->json(['route' => $borderCrossingRoute], $status);
    }

    #[Route('/routingtest/', name: 'app_borders_test')]
    public function routetest(): Response
    {
        return new Response("Test");
    }
}
