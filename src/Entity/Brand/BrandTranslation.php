<?php

declare(strict_types=1);

namespace App\Entity\Brand;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Resource\Model\AbstractTranslation;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TranslationInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_brand_translation')]
class BrandTranslation extends AbstractTranslation implements ResourceInterface, TranslationInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
