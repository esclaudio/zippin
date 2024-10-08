<?php

namespace App\Exceptions;

use Exception;

class InvalidOrderTotalException extends Exception
{
    public function __construct(float $calculatedTotal, float $receivedTotal)
    {
        parent::__construct("The calculated total ($calculatedTotal) does not match the received total ($receivedTotal).");
    }
}
