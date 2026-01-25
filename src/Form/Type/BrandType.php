<?php

declare(strict_types=1);

namespace App\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class BrandType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventSubscriber(new AddCodeFormSubscriber())
            ->add('name', TextType::class, [
                'label' => 'sylius.ui.name',
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
            ])
            ->add('translations', ResourceTranslationsType::class, [
                'entry_type' => BrandTranslationType::class,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.ui.channels',
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_brand';
    }
}
