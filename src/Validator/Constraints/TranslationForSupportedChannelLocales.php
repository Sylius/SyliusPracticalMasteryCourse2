<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

final class TranslationForSupportedChannelLocales extends Constraint
{
    public string $message = 'sylius.brand.translation.supported_channel_locales';

    public function validatedBy(): string
    {
        return TranslationForSupportedChannelLocalesValidator::class;
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
