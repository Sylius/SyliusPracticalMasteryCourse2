<?php

declare(strict_types=1);

namespace App\Entity\Brand;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Resource\Model\ResourceInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_brand_taxon')]
class BrandTaxon implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'brandTaxons')]
    private ?Brand $brand = null;

    #[ORM\ManyToOne(targetEntity: TaxonInterface::class)]
    private ?TaxonInterface $taxon = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): void
    {
        $this->brand = $brand;
    }

    public function getTaxon(): ?TaxonInterface
    {
        return $this->taxon;
    }

    public function setTaxon(?TaxonInterface $taxon): void
    {
        $this->taxon = $taxon;
    }
}
