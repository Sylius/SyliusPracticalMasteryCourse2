<?php

declare(strict_types=1);

namespace App\SM;

interface BrandTransitions
{
    public const string GRAPH = 'sylius_brand';

    public const string TRANSITION_APPROVE = 'approve';

    public const string TRANSITION_REJECT = 'reject';

    public const string TRANSITION_SUSPEND = 'suspend';
}
