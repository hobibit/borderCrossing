<?php

declare(strict_types=1);

namespace App\Service\BordersCrossings;

class CountriesJsonNormalizer
{
    public function normnalize(string $json): array
    {
        $countriesArray = json_decode($json, true);

        $countriesList = [];

        foreach ($countriesArray as $country) {
            if (isset($country['cca3']) && isset($country['borders']))
                $countriesList[$country['cca3']] = $country['borders'];
        }

        return $countriesList;
    }
}
