<?php

declare(strict_types=1);

namespace App\Service\BordersCrossings\Tests;

use App\Service\BordersCrossings\BordersCrossingsService;
use PHPUnit\Framework\TestCase;

class BordersCrossingsServiceTest extends TestCase
{
    private BordersCrossingsService $bordersCrossingsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->bordersCrossingsService = new BordersCrossingsService();
    }

    public function testRouteStartWithOriginCountry(): void
    {
        //given
        $origin = 'TEST1';
        $destination = 'TEST3';
        $countries = [
            'TEST1' => ['TEST2', 'TEST3'],
        ];

        //when
        $resultRoute = $this->bordersCrossingsService->getBorderCrossingRoute($origin, $destination, $countries);

        //then
        $firstCountryInRoute = reset($resultRoute);
        $this->assertEquals($origin, $firstCountryInRoute);
    }

    public function testFindNextCountriesInRouteReturnCorrectCountries(): void
    {
        //given
        $route = ['TEST1', 'TEST2'];
        $countries = [
            'TEST1' => ['TEST2', 'TEST3'],
            'TEST2' => ['TEST1', 'TEST3'],
            'TEST3' => ['TEST1', 'TEST2'],
        ];
        $expectedCountries = ['TEST1', 'TEST3'];

        //when
        $returnedCountries = $this->bordersCrossingsService->findNextCountriesInRoute($route, $countries);

        //then
        $this->assertEquals($expectedCountries, $expectedCountries);
    }

    public function testFindNextCountriesInRouteWillNotReturnSameCountryTwice(): void
    {
        //given
        $routeOne = ['TEST1'];
        $routeTwo = ['TEST2'];
        $routeThree = ['TEST3'];
        $routeFour = ['TEST1'];

        $countries = [
            'TEST1' => ['TEST2', 'TEST3'],
            'TEST2' => ['TEST1', 'TEST3'],
            'TEST3' => ['TEST1', 'TEST2'],
        ];
        $expectedCountriesOne = ['TEST2', 'TEST3'];
        $expectedCountriesTwo = ['TEST1'];
        $expectedCountriesThree = [];
        $expectedCountriesFour = [];

        //when
        $returnedCountriesOne = $this->bordersCrossingsService->findNextCountriesInRoute($routeOne, $countries);
        $returnedCountriesTwo = $this->bordersCrossingsService->findNextCountriesInRoute($routeTwo, $countries);
        $returnedCountriesThree = $this->bordersCrossingsService->findNextCountriesInRoute($routeThree, $countries);
        $returnedCountriesFour = $this->bordersCrossingsService->findNextCountriesInRoute($routeFour, $countries);

        //then
        $this->assertEquals($expectedCountriesOne, $returnedCountriesOne);
        $this->assertEquals($expectedCountriesTwo, $returnedCountriesTwo);
        $this->assertEquals($expectedCountriesThree, $returnedCountriesThree);
        $this->assertEquals($expectedCountriesFour, $returnedCountriesFour);
    }

    public function testSplitRouteFindCorrectRoute(): void
    {
        //given
        $startRoutes = [['TEST1']];
        $countries = [
            'TEST1' => ['TEST2', 'TEST3'],
            'TEST2' => ['TEST4', 'TEST5'],
            'TEST3' => ['TEST6', 'TEST7'],
        ];
        $destination = 'TEST7';
        $expectedPath = ['TEST1', 'TEST3', 'TEST7'];

        //when
        $resultPath = $this->bordersCrossingsService->splitRouteUntilDestinationIsFound($startRoutes, $destination, $countries);

        //then
        $this->assertEquals($expectedPath, $resultPath);
    }

    public function testSplitRouteWillReturnEmptyArrayIfNoRouteFound(): void
    {
        //given
        $startRoutes = [['TEST1']];
        $countries = [
            'TEST1' => ['TEST2', 'TEST3'],
            'TEST2' => ['TEST4', 'TEST5'],
            'TEST3' => ['TEST6', 'TEST7'],
        ];
        $destination = 'NOWHERE';
        $expectedPath = [];

        //when
        $resultPath = $this->bordersCrossingsService->splitRouteUntilDestinationIsFound($startRoutes, $destination, $countries);

        //then
        $this->assertEquals($expectedPath, $resultPath);
    }
}
