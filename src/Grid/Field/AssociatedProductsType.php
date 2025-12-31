<?php

declare(strict_types=1);

namespace App\Grid\Field;

use App\Entity\Brand\Brand;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AssociatedProductsType implements FieldTypeInterface
{
    public function render(Field $field, $data, array $options): string
    {
        if (!$data instanceof Brand) {
            return '';
        }

        return (string) $data->getProducts()->count();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // TODO: Implement configureOptions() method.
    }
}
