<?php

namespace App\Repository;

use App\Entity\BannedUsers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BannedUsers>
 *
 * @method BannedUsers|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannedUsers|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannedUsers[]    findAll()
 * @method BannedUsers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannedUsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BannedUsers::class);
    }

    //    /**
    //     * @return BannedUsers[] Returns an array of BannedUsers objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BannedUsers
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
