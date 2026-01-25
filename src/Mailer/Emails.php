<?php

declare(strict_types=1);

namespace App\Mailer;

use Sylius\Bundle\CoreBundle\Mailer\Emails as BaseEmails;

interface Emails extends BaseEmails
{
    public const string BRAND_APPROVAL = 'brand_approval';
}
