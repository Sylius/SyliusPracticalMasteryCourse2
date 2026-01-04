<?php

declare(strict_types=1);

namespace App\Grid;

use App\Entity\Brand\Brand;
use Sylius\Bundle\GridBundle\Builder\Action\Action;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\BulkActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\ItemActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\MainActionGroup;
use Sylius\Bundle\GridBundle\Builder\Field\DateTimeField;
use Sylius\Bundle\GridBundle\Builder\Field\Field;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\Filter\Filter;
use Sylius\Bundle\GridBundle\Builder\GridBuilderInterface;
use Sylius\Bundle\GridBundle\Grid\AbstractGrid;
use Sylius\Component\Grid\Attribute\AsGrid;

#[AsGrid(
    resourceClass: Brand::class,
    name: 'sylius_admin_brand',
)]
final class AdminBrandGrid extends AbstractGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder->setRepositoryMethod('createAdminGridQueryBuilder');
        $gridBuilder->orderBy('createdAt', 'desc');

        $gridBuilder
            ->addField(StringField::create('id')->setLabel('sylius.ui.id'))
            ->addField(StringField::create('code')->setLabel('sylius.ui.code'))
            ->addField(StringField::create('name')->setLabel('sylius.ui.name'))
            ->addField(DateTimeField::create('createdAt')->setSortable(true)->setLabel('sylius.ui.created_at'))
            ->addField(DateTimeField::create('updatedAt')->setSortable(true)->setLabel('sylius.ui.updated_at'))
            ->addField(TwigField::create('enabled', '@SyliusAdmin/shared/grid/field/boolean.html.twig')->setLabel('sylius.ui.enabled'))
            ->addField(Field::create('associated_products', 'associated_products')->setLabel('sylius.ui.associated_products'))
        ;

        $gridBuilder
            ->addActionGroup(
                MainActionGroup::create(
                    Action::create('create', 'create')
                )
            )
            ->addActionGroup(
                ItemActionGroup::create(
                    Action::create('show', 'show'),
                    Action::create('update', 'update'),
                    Action::create('delete', 'delete'),
                    Action::create('show_products', 'show_products')->setOptions([
                        'link' => [
                            'route' => 'sylius_shop_branded_products_index',
                            'parameters' => [
                                'code' => 'resource.code'
                            ]
                        ]
                    ])
                )
            )
            ->addActionGroup(
                BulkActionGroup::create(
                    Action::create('delete', 'delete'),
                    Action::create('export', 'export'),
                )
            )
        ;

        $gridBuilder
            ->addFilter(
                Filter::create('search', 'string')->setOptions([
                    'fields' => ['code', 'name'],
                ])->setLabel('sylius.ui.search')
            )
            ->addFilter(
                Filter::create('enabled', 'boolean')->setLabel('sylius.ui.enabled')
            )
            ->addFilter(
                Filter::create('createdAt', 'date')->setOptions([
                    'field' => 'createdAt',
                    'inclusive_to' => true,
                ])->setLabel('sylius.ui.created_at')
            )
            ->addFilter(
                Filter::create('update_delay', 'update_delay')->setLabel('sylius.ui.update_delay')
            )
        ;
    }
}
