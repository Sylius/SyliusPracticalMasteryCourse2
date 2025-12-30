<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class MenuBuilder implements EventSubscriberInterface
{
    public function buildMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $catalog = $menu->getChild('catalog');
        $catalog
            ->addChild('sylius.brand', ['route' => 'sylius_admin_brand_index'])
            ->setLabel('sylius.ui.brands')
        ;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.menu.admin.main' => 'buildMenu',
        ];
    }
}
