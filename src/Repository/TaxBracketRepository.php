<?php

namespace App\Repository;

use App\Entity\TaxBracket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TaxBracketRepository extends ServiceEntityRepository
{
    public function __construct(private ManagerRegistry $registry)
    {
        parent::__construct($registry, TaxBracket::class);
    }

    /**
     *
     * @return TaxBracket[] Returns an array of TaxBracket objects
     */
    public function getAllOrdered(): array
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.minIncome', 'ASC')
            ->getQuery()
            ->getResult();
    }
}