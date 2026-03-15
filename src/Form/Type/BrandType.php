<?php

declare(strict_types=1);

namespace App\Form\Type;

use Sylius\Bundle\AdminBundle\Form\Type\TaxonAutocompleteType;
use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\LocaleBundle\Form\Type\LocaleChoiceType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\UX\LiveComponent\Form\Type\LiveCollectionType;

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
            ->add('defaultLocale', LocaleChoiceType::class, [
                'label' => 'sylius.ui.default_locale',
                'required' => true,
                'placeholder' => null,
            ])
            ->add('contactEmail', EmailType::class, [
                'label' => 'sylius.ui.email',
                'required' => true,
            ])
            ->add('images', LiveCollectionType::class, [
                'entry_type' => BrandImageType::class,
                'label' => 'sylius.ui.images',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'block_name' => 'entry',
            ])
            ->add('mainTaxon', TaxonAutocompleteType::class, [
                'label' => 'sylius.ui.main_taxon',
                'multiple' => false,
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
                $brand = $event->getData();
                $form = $event->getForm();

                $form->add('brandTaxons', BrandTaxonAutocompleteChoiceType::class, [
                    'label' => 'sylius.ui.taxons',
                    'brand' => $brand,
                    'multiple' => true,
                ]);
            })
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_brand';
    }
}
