<?php

declare(strict_types=1);

namespace App\Form\Transformer;

use App\Entity\Brand\Brand;
use App\Entity\Brand\BrandTaxon;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\DataTransformerInterface;

final readonly class BrandTaxonToTaxonTransformer implements DataTransformerInterface
{
    public function __construct(
        private FactoryInterface $brandTaxonFactory,
        private RepositoryInterface $brandTaxonRepository,
        private Brand $brand,
    ) {
    }

    public function transform(mixed $value): ?TaxonInterface
    {
        if (null === $value) {
            return null;
        }

        return $value->getTaxon();
    }

    public function reverseTransform(mixed $value): ?BrandTaxon
    {
        if (null === $value) {
            return null;
        }

        $brandTaxon = $this->brandTaxonRepository->findOneBy([
            'taxon' => $value,
            'brand' => $this->brand,
        ]);

        if (!$brandTaxon instanceof BrandTaxon) {
            /** @var BrandTaxon $brandTaxon */
            $brandTaxon = $this->brandTaxonFactory->createNew();
            $brandTaxon->setBrand($this->brand);
            $brandTaxon->setTaxon($value);
        }

        return $brandTaxon;
    }
}
