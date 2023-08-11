<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BorderCrossingControllerTest extends WebTestCase
{
    protected static function getKernelClass(): string
    {
        return 'App\Kernel';
    }

    public function testBorderCrosingControllerFindCorrectRoute(): void
    {
        //given
        $client = static::createClient();
        $expectedResponce = json_encode(['route' => ['CZE', 'AUT', 'ITA']]);

        //when
        $client->request('GET', '/routing/CZE/ITA');

        //then
        $this->assertResponseIsSuccessful();

        $responce = $client->getResponse()->getContent();
        self::assertEquals($expectedResponce, $responce);
    }

    public function testBorderCrosingControllerReturn400whenNoRouteFound(): void
    {
        //given
        $client = static::createClient();

        //when
        $client->request('GET', '/routing/CZE/USA');

        //then
        self::assertResponseStatusCodeSame(400);
    }
}
