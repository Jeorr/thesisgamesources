<?php

declare(strict_types=1);

namespace Server\Game\Utility;

use Server\Game\Models\Location;

/**
 *
 */
class MathUtility
{

    public static function calculateDistanceBetweenLocations(Location $location1, Location $location2): float
    {
        return sqrt(pow($location2->getY() - $location1->getY(), 2) + pow($location2->getX() - $location1->getX(), 2));
    }

    public static function calculateTimeToPassDistance(float $distance): float
    {
        return (float)(5 + ($distance / 50));
    }

    /**
     * @param float $chance
     * @return bool
     */
    public static function isChanceSuccessful(float $chance): bool
    {
        return (float)(mt_rand(0, 100000) / 1000) <= $chance;
    }
}