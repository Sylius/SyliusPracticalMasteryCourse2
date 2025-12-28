<?php

declare(strict_types=1);

namespace App\Repository;

use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductRepository as BaseProductRepository;

final class ProductRepository extends BaseProductRepository
{
    public function findBranded(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.brand IS NOT NULL')
            ->getQuery()
            ->getResult()
        ;
    }
}
