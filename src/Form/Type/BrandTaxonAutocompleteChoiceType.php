<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\Brand\Brand;
use App\Form\Transformer\BrandTaxonToTaxonTransformer;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\RecursiveTransformer;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class BrandTaxonAutocompleteChoiceType extends AbstractType
{
    public function __construct(
        private readonly FactoryInterface $brandTaxonFactory,
        private readonly RepositoryInterface $brandTaxonRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['multiple']) {
           $builder->addModelTransformer(
               new RecursiveTransformer(
                   new BrandTaxonToTaxonTransformer(
                       $this->brandTaxonFactory,
                       $this->brandTaxonRepository,
                       $options['brand'],
                   )
               )
           );
        }

        if (!$options['multiple']) {
            $builder->addModelTransformer(
                new BrandTaxonToTaxonTransformer(
                    $this->brandTaxonFactory,
                    $this->brandTaxonRepository,
                    $options['brand'],
                )
            );
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'resource' => 'sylius.taxon',
            'choice_name' => 'name',
            'choice_value' => 'code',
        ]);

        $resolver
            ->setRequired('brand')
            ->setAllowedTypes('brand', Brand::class)
        ;
    }

    public function getParent(): string
    {
        return ResourceAutocompleteChoiceType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_brand_taxon_autocomplete_choice';
    }
}
