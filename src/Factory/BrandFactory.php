<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Brand\Brand;
use Sylius\Resource\Factory\FactoryInterface;

final class BrandFactory implements FactoryInterface
{
    public function __construct(
        protected FactoryInterface $decoratedFactory,
    ) {
    }

    public function createNew(): Brand
    {
        /** @var Brand $brand */
        $brand = $this->decoratedFactory->createNew();

        return $brand;
    }

    public function createFromCodeWithName(string $code): Brand
    {
        $brand = $this->createNew();
        $brand->setCode($code);
        $brand->setName(ucfirst($code));

        return $brand;
    }
}
