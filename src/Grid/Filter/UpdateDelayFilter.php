<?php

declare(strict_types=1);

namespace App\Grid\Filter;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Filtering\FilterInterface;

final class UpdateDelayFilter implements FilterInterface
{
    public function apply(DataSourceInterface $dataSource, string $name, $data, array $options): void
    {
        $delayDays = $data['delay_days'] ?? '';
        if ('' === $delayDays) {
            return;
        }

        $days = (int) $delayDays;
        $expressionBuilder = $dataSource->getExpressionBuilder();
        $queryBuilder = $dataSource->getQueryBuilder();

        $dataSource->restrict(
            $expressionBuilder->andX(
                $expressionBuilder->isNotNull('createdAt'),
                $expressionBuilder->isNotNull('updatedAt'),
            )
        );

        $rootAlias = $queryBuilder->getRootAliases()[0];

        if (0 === $days) {
            $queryBuilder->andWhere(
                sprintf('%s.updatedAt = %s.createdAt', $rootAlias, $rootAlias)
            );
        } else {
            $queryBuilder->andWhere(
                sprintf('%s.updatedAt >= DATE_ADD(%s.createdAt, %d, \'day\')', $rootAlias, $rootAlias, $days)
            );
        }
    }
}
