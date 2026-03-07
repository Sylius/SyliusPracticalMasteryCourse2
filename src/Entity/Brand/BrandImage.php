<?php

declare(strict_types=1);

namespace App\Entity\Brand;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ImageInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_brand_image')]
class BrandImage implements ImageInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'type', type: 'string', nullable: true)]
    private ?string $type = null;

    private ?\SplFileInfo $file = null;

    #[ORM\Column(name: 'path', type: 'string', nullable: true)]
    private ?string $path = null;

    #[ORM\ManyToOne(targetEntity: Brand::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: 'owner_id', referencedColumnName: 'id', nullable: true, onDelete: 'CASCADE')]
    private ?Brand $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getFile(): ?\SplFileInfo
    {
        return $this->file;
    }

    public function setFile(?\SplFileInfo $file): void
    {
        $this->file = $file;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getOwner(): ?Brand
    {
        return $this->owner;
    }

    /**
     * @param ?Brand $owner
     * @return void
     */
    public function setOwner($owner): void
    {
        $this->owner = $owner;
    }

    public function hasFile(): bool
    {
        return null !== $this->file;
    }
}
