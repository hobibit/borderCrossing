<?php

declare(strict_types=1);

namespace App\Service\BordersCrossings;

class BordersCrossingsService
{

    protected static $visitedCountries = [];

    public function __construct()
    {
        self::$visitedCountries = [];
    }

    public function getBorderCrossingRoute(string $origin, string $destination, array $countries): array
    {
        $route = [[$origin]];

        return $this->splitRouteUntilDestinationIsFound($route, $destination, $countries);
    }

    public function findNextCountriesInRoute(array $route, array $countries): array
    {
        $lastCountryinRoute = end($route);
        if (!isset($countries[$lastCountryinRoute])) {
            return [];
        }
        $borderCountries = $countries[$lastCountryinRoute];
        $returnCountries = [];

        foreach ($borderCountries as $borderCountry) {
            if (!in_array($borderCountry, self::$visitedCountries)) {
                $returnCountries[] = $borderCountry;
                self::$visitedCountries[] = $borderCountry;
            }
        }

        return $returnCountries;
    }

    public function splitRouteUntilDestinationIsFound(array $routes, string $destination, array $countries): array
    {
        $routesToExtend = [];
        foreach ($routes as $route) {
            $nextCountriesInRoute = $this->findNextCountriesInRoute($route, $countries);

            if (count($nextCountriesInRoute) === 0) {
                continue;
            }

            foreach ($nextCountriesInRoute as $nextCountryInRoute) {

                $newRoute = $route;
                $newRoute[] = $nextCountryInRoute;

                if ($nextCountryInRoute === $destination) {
                    return $newRoute;
                }

                $routesToExtend[] = $newRoute;
            }
        }

        if (count($routesToExtend) === 0) {
            return [];
        }

        return $this->splitRouteUntilDestinationIsFound($routesToExtend, $destination, $countries);
    }

}
