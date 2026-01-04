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
                    'sylius.form.update_delay_filter.delay_days.never_updated' => 0,
                    'sylius.form.update_delay_filter.delay_days.1_day' => 1,
                    'sylius.form.update_delay_filter.delay_days.2_day' => 2,
                    'sylius.form.update_delay_filter.delay_days.3_day' => 3,
                    'sylius.form.update_delay_filter.delay_days.4_day' => 4,
                    'sylius.form.update_delay_filter.delay_days.7_day' => 7,
                ],
                'placeholder' => 'sylius.ui.select_an_option',
                'required' => false,
                'label' => 'sylius.ui.update_delay'
            ])
        ;
    }
}
