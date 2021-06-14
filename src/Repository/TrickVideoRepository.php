<?php


namespace App\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\TrickVideo;

class TrickVideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)

    {
        parent::__construct($registry, TrickVideo::class);

    }
}