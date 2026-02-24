<?php

namespace App\Services;


class LocationService
{
    private const CHENNAI_LAT = 13.0827;
    private const CHENNAI_LNG = 80.2707;
    private const MAX_RADIUS_KM = 20;

    /**
     * Haversine formula to calculate distance between two coordinates.
     */
    public function distanceFromChennai(float $lat, float $lng): float
    {
        return $this->haversine(self::CHENNAI_LAT, self::CHENNAI_LNG, $lat, $lng);
    }

    public function isWithinChennai(float $lat, float $lng): bool
    {
        return $this->distanceFromChennai($lat, $lng) <= self::MAX_RADIUS_KM;
    }

    public function assertWithinChennai(float $lat, float $lng): void
    {
        if (! $this->isWithinChennai($lat, $lng)) {
            throw new \App\Exceptions\LocationOutOfRangeException(
                "Service location must be within " . self::MAX_RADIUS_KM . "km of Chennai city centre."
            );
        }
    }

    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}