<?php

declare(strict_types=1);

namespace App\Twig\Component;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\UX\LiveComponent\Attribute\LiveAction;

final class BrandFormComponent
{
    use LiveCollectionTrait;
    use TemplatePropTrait;

    /** @use ResourceFormComponentTrait<ResourceInterface> */
    use ResourceFormComponentTrait {
        initialize as public __construct;
    }

    #[LiveAction]
    public function generateBrandCode(): void
    {
        $this->formValues['code'] = str_replace(' ', '_', strtolower($this->formValues['name']));
    }
}
