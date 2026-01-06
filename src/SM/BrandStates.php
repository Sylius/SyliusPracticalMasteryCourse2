<?php

declare(strict_types=1);

namespace App\SM;

interface BrandStates
{
    public const string STATE_NEW = 'new';

    public const string STATE_APPROVED = 'approved';

    public const string STATE_REJECTED = 'rejected';

    public const string STATE_SUSPENDED = 'suspended';
}
