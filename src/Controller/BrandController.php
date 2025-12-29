<?php

declare(strict_types=1);

namespace App\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class BrandController extends ResourceController
{
    public function showAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);

        $this->isGrantedOr403($configuration, ResourceActions::SHOW);
        $resource = $this->findOr404($configuration);

        $event = $this->eventDispatcher->dispatch(ResourceActions::SHOW, $configuration, $resource);
        $eventResponse = $event->getResponse();
        if (null !== $eventResponse) {
            return $eventResponse;
        }

        if ($configuration->isHtmlRequest()) {
            dump('Hello!');
            return $this->render($configuration->getTemplate(ResourceActions::SHOW . '.html'), [
                'configuration' => $configuration,
                'metadata' => $this->metadata,
                'resource' => $resource,
                $this->metadata->getName() => $resource,
            ]);
        }

        return $this->createRestView($configuration, $resource);
    }
}
