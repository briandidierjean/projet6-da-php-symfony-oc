<?php


namespace App\Repository;


use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)

    {
        parent::__construct($registry, Trick::class);

    }
}