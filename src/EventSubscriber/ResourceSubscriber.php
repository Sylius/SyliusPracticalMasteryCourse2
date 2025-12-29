<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Brand\Brand;
use App\Entity\Product\Product;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Resource\Model\CodeAwareInterface;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

final class ResourceSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'onProductShow',
            'sylius.product.index' => 'onResourceIndex',
            'sylius.brand.index' => 'onResourceIndex',
            'sylius.brand.initialize_create' => 'onBrandShow',
            'sylius.brand.pre_create' => 'onBrandPreCreate',
        ];
    }

    public function onProductShow(GenericEvent $event): void
    {
        $product = $event->getSubject();
        if (!$product instanceof Product) {
            return;
        }

        if ($product->getCode() === 'Adventurous_Aurora_Cap') {
            $response = new RedirectResponse('/admin');
            $event->setResponse($response);
        }
    }

    public function onResourceIndex(GenericEvent $event): void
    {
        $list = $event->getSubject();

        if ($list instanceof ResourceGridView) {
            $list = $list->getData();
        }

        /** @var CodeAwareInterface $resource */
        foreach ($list as $resource) {
            dump($resource->getCode());
        }
    }

    public function onBrandShow(GenericEvent $event): void
    {
        $brand = $event->getSubject();
        dump($brand);
    }

    public function onBrandPreCreate(GenericEvent $event): void
    {
        $brand = $event->getSubject();
        if (!$brand instanceof Brand) {
            return;
        }

        $event->stopPropagation();
        $event->setMessage('Cannot create any more Brands.');
        $event->setMessageType(GenericEvent::TYPE_ERROR);

        $response = new RedirectResponse('/admin');
        $event->setResponse($response);
    }
}
