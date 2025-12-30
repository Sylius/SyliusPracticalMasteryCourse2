<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Brand\Brand;
use Doctrine\ORM\QueryBuilder;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

final class BrandRepository extends EntityRepository implements RepositoryInterface
{
    public function createEnabledQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.enabled = :enabled')
            ->setParameter('enabled', true)
        ;
    }

    public function findEnabled(): array
    {
        return $this->createEnabledQueryBuilder()
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneByCode(string $code): ?Brand
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.code = :code')
            ->setParameter('code', $code)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
