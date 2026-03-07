<?php

declare(strict_types=1);

namespace App\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

final class BrandImageType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'sylius.ui.logo' => 'logo',
                    'sylius.ui.cover' => 'cover',
                ],
                'label' => 'sylius.ui.type',
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'label' => 'sylius.ui.image',
                'required' => true,
            ])
        ;
    }
}
