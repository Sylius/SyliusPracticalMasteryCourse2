<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Entity\Brand\Brand;
use App\Entity\Brand\BrandTranslation;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Webmozart\Assert\Assert;

final class TranslationForSupportedChannelLocalesValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        $brand = $value;

        Assert::isInstanceOf($brand, Brand::class);
        Assert::isInstanceOf($constraint, TranslationForSupportedChannelLocales::class);

        $supportedLocaleCodes = [];

        foreach ($brand->getChannels() as $channel) {
            $supportedLocaleCodes[] = $channel->getDefaultLocale()?->getCode();

            foreach ($channel->getLocales() as $locale) {
                $supportedLocaleCodes[] = $locale->getCode();
            }
        }

        $implementedLocaleCodes = [];

        /** @var BrandTranslation $translation */
        foreach ($brand->getTranslations() as $translation) {
            if (null === $translation->getDescription() || '' === $translation->getDescription()) {
                continue;
            }

            $implementedLocaleCodes[] = $translation->getLocale();
        }

        $missingLocaleCodes = array_diff(
            array_unique($supportedLocaleCodes),
            array_unique($implementedLocaleCodes),
        );

        foreach ($missingLocaleCodes as $missingLocaleCode) {
            $this->context
                ->buildViolation($constraint->message)
                ->setParameter('%locale%', $missingLocaleCode)
                ->addViolation();
        }
    }
}
