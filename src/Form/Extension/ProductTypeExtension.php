<?php

declare(strict_types=1);

namespace App\Form\Extension;

use App\Entity\Brand\Brand;
use App\Repository\BrandRepository;
use Sylius\Bundle\AdminBundle\Form\Type\ProductType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('brand', EntityType::class, [
                'label' => 'sylius.ui.brand',
                'class' => Brand::class,
                'choice_label' => 'name',
                'placeholder' => 'sylius.ui.choose_brand',
                'query_builder' => function (BrandRepository $repository) {
                    return $repository->createEnabledQueryBuilder();
                }
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            ProductType::class,
        ];
    }
}
