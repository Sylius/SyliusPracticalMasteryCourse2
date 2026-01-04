<?php

declare(strict_types=1);

namespace App\Grid;

use App\Entity\Brand\Brand;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Event\GridDefinitionConverterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class AdminProductGrid implements EventSubscriberInterface
{
    public function __invoke(GridDefinitionConverterEvent $event): void
    {
        $grid = $event->getGrid();

        $grid->removeField('mainTaxon');
        $grid->getField('image')->setPosition(1);
        $grid->getField('enabled')->setPosition(2);

        $brandFilter = Filter::fromNameAndType('brand', 'entity');
        $brandFilter->setFormOptions([
            'class' => Brand::class,
            'choice_label' => 'name',
        ]);

        $grid->addFilter($brandFilter);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.grid.admin_product' => '__invoke',
        ];
    }
}
