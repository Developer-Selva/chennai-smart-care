<?php

// -------------------------------------------------------
// app/Exceptions/LocationOutOfRangeException.php
// -------------------------------------------------------

namespace App\Exceptions;

class LocationOutOfRangeException extends \Exception
{
    public function __construct(string $message = "Location is outside the service area.")
    {
        parent::__construct($message, 422);
    }
}