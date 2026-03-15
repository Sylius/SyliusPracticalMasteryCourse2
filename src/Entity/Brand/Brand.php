<?php

declare(strict_types=1);

namespace App\Entity\Brand;

use App\Entity\Product\Product;
use App\SM\BrandStates;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Component\Channel\Model\ChannelsAwareInterface;
use Sylius\Component\Core\Model\ImageInterface;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Resource\Model\CodeAwareInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Model\TimestampableInterface;
use Sylius\Resource\Model\TimestampableTrait;
use Sylius\Resource\Model\ToggleableInterface;
use Sylius\Resource\Model\ToggleableTrait;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslatableTrait;
use Sylius\Resource\Model\TranslationInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_brand')]
class Brand implements
    ResourceInterface,
    CodeAwareInterface,
    ToggleableInterface,
    TimestampableInterface,
    TranslatableInterface,
    ChannelsAwareInterface,
    ImagesAwareInterface
{
    use ToggleableTrait, TimestampableTrait;

    use TranslatableTrait {
        __construct as private initializeTranslationsCollection;
        getTranslation as private doGetTranslation;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'brand')]
    private Collection $products;

    #[ORM\Column(type: 'boolean')]
    /** @var bool */
    protected $enabled = true;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Gedmo\Timestampable(on: 'create')]
    /** @var \DateTimeInterface|null */
    protected $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    /** @var \DateTimeInterface|null */
    protected $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $state = BrandStates::STATE_NEW;

    #[ORM\ManyToMany(targetEntity: ChannelInterface::class)]
    #[ORM\JoinTable(
        name: 'sylius_brand_channel',
        joinColumns: [
            new ORM\JoinColumn(name: 'brand_id', referencedColumnName: 'id')
        ],
        inverseJoinColumns: [
            new ORM\JoinColumn(name: 'channel_id', referencedColumnName: 'id')
        ]
    )]
    private Collection $channels;

    #[ORM\ManyToOne(targetEntity: LocaleInterface::class)]
    #[ORM\JoinColumn(name: 'default_locale_id', referencedColumnName: 'id', nullable: true)]
    private ?LocaleInterface $defaultLocale = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $contactEmail = null;

    #[ORM\OneToMany(targetEntity: BrandImage::class, mappedBy: 'owner', cascade: ['all'], orphanRemoval: true)]
    private Collection $images;

    #[ORM\ManyToOne(targetEntity: TaxonInterface::class)]
    #[ORM\JoinColumn(name: 'main_taxon_id', referencedColumnName: 'id', nullable: true)]
    private ?TaxonInterface $mainTaxon = null;

    #[ORM\OneToMany(targetEntity: BrandTaxon::class, mappedBy: 'brand', cascade: ['all'], orphanRemoval: true)]
    private Collection $brandTaxons;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->brandTaxons = new ArrayCollection();
        $this->initializeTranslationsCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setBrand($this);
        }
    }

    public function removeProduct(Product $product): void
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->setBrand(null);
        }
    }

    public function setDescription(string $description): void
    {
        $this->getTranslation()->setDescription($description);
    }

    public function getDescription(): ?string
    {
        return $this->getTranslation()->getDescription();
    }

    public function getTranslation(?string $locale = null): BrandTranslation
    {
        /** @var BrandTranslation $translation */
        $translation = $this->doGetTranslation($locale);

        return $translation;
    }

    public function getState(): ?string
    {
        return $this->state;
    }

    public function setState(?string $state): void
    {
        $this->state = $state;
    }

    public function getDefaultLocale(): ?LocaleInterface
    {
        return $this->defaultLocale;
    }

    public function setDefaultLocale(?LocaleInterface $defaultLocale): void
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    protected function createTranslation(): TranslationInterface
    {
        return new BrandTranslation();
    }

    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function hasChannel(ChannelInterface $channel): bool
    {
        return $this->channels->contains($channel);
    }

    public function addChannel(ChannelInterface $channel): void
    {
        if (!$this->hasChannel($channel)) {
            $this->channels->add($channel);
        }
    }

    public function removeChannel(ChannelInterface $channel): void
    {
        if ($this->hasChannel($channel)) {
            $this->channels->removeElement($channel);
        }
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function getImagesByType(string $type): Collection
    {
        return $this->images->filter(function (ImageInterface $image) use ($type) {
            return $image->getType() === $type;
        });
    }

    public function hasImages(): bool
    {
        return !$this->images->isEmpty();
    }

    public function hasImage(ImageInterface $image): bool
    {
        return $this->images->contains($image);
    }

    public function addImage(ImageInterface $image): void
    {
        if (!$this->hasImage($image)) {
            $this->images->add($image);
            $image->setOwner($this);
        }
    }

    public function removeImage(ImageInterface $image): void
    {
        if ($this->hasImage($image)) {
            $this->images->removeElement($image);
            $image->setOwner(null);
        }
    }

    public function getMainTaxon(): ?TaxonInterface
    {
        return $this->mainTaxon;
    }

    public function setMainTaxon(?TaxonInterface $mainTaxon): void
    {
        $this->mainTaxon = $mainTaxon;
    }

    public function getBrandTaxons(): Collection
    {
        return $this->brandTaxons;
    }

    public function hasBrandTaxon(BrandTaxon $brandTaxon): bool
    {
        return $this->brandTaxons->contains($brandTaxon);
    }

    public function addBrandTaxon(BrandTaxon $brandTaxon): void
    {
        if (!$this->hasBrandTaxon($brandTaxon)) {
            $this->brandTaxons->add($brandTaxon);
            $brandTaxon->setBrand($this);
        }
    }

    public function removeBrandTaxon(BrandTaxon $brandTaxon): void
    {
        if ($this->hasBrandTaxon($brandTaxon)) {
            $this->brandTaxons->removeElement($brandTaxon);
            $brandTaxon->setBrand(null);
        }
    }
}
