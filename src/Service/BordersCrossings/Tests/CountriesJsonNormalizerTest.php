<?php

declare(strict_types=1);

namespace App\Service\BordersCrossings\Tests;

use App\Service\BordersCrossings\CountriesJsonNormalizer;
use PHPUnit\Framework\TestCase;

class CountriesJsonNormalizerTest extends TestCase
{
    public function testJsonNormalizer(): void
    {
        //given
        $normalizer = new CountriesJsonNormalizer();
        $inputArray = [
            [
                'cca3' => 'TEST1',
                'borders' => ['TEST2', 'TEST3'],
            ],
            [
                'cca3' => 'TEST2',
                'borders' => ['TEST1', 'TEST3'],
            ],
            [
                'cca3' => 'TEST3',
                'borders' => ['TEST1', 'TEST2'],
            ],
        ];
        $inputJson = json_encode($inputArray);

        $expectedResult = [
            'TEST1' => ['TEST2', 'TEST3'],
            'TEST2' => ['TEST1', 'TEST3'],
            'TEST3' => ['TEST1', 'TEST2'],
        ];

        //when
        $result = $normalizer->normnalize($inputJson);

        //then
        $this->assertEquals($expectedResult, $result);
    }
}
