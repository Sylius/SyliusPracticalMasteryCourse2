<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class UpdateDelayFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('delay_days', ChoiceType::class, [
                'choices' => [
                    'Never updated' => 0,
                    '>= 1 day' => 1,
                    '>= 2 days' => 2,
                    '>= 3 days' => 3,
                    '>= 4 days' => 4,
                    '>= 7 days' => 7,
                ],
                'placeholder' => 'sylius.ui.select_an_option',
                'required' => false,
            ])
        ;
    }
}
