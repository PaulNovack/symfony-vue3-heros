<?php

namespace App\Repository;

use App\Entity\Hero;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hero>
 */
class HeroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hero::class);
    }

    public function findByNameStartsWith(string $name, int $limit): array
    {
        return $this->createQueryBuilder('h')
            ->where('h.name LIKE :name')
            ->setParameter('name', $name.'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
