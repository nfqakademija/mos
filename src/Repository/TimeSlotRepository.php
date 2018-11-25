<?php

namespace App\Repository;

use App\Entity\TimeSlot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TimeSlot|null find($id, $lockMode = null, $lockVersion = null)
 * @method TimeSlot|null findOneBy(array $criteria, array $orderBy = null)
 * @method TimeSlot[]    findAll()
 * @method TimeSlot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimeSlotRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TimeSlot::class);
    }
}
