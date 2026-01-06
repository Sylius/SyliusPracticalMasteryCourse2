<?php

declare(strict_types=1);

namespace App\SM;

use Sylius\Component\Shipping\ShipmentTransitions as BaseShipmentTransitions;

interface ShipmentTransitions extends BaseShipmentTransitions
{
    public const string TRANSITION_PREPARE = 'prepare';
}
