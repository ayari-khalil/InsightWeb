<?php

namespace App\Repository;

use App\Entity\Ecole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ecole>
 *
 * @method Ecole|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ecole|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ecole[]    findAll()
 * @method Ecole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ecole::class);
    }

    //    /**
    //     * @return Ecole[] Returns an array of Ecole objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Ecole
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findAllSortedBy(string $sortBy): array
{
    $queryBuilder = $this->createQueryBuilder('e');

    switch ($sortBy) {
        case 'nom':
            $queryBuilder->orderBy('e.nom', 'ASC');
            break;
        case 'id':
            $queryBuilder->orderBy('e.id', 'ASC');
            break;
        case 'nb_professeur':
            // Si vous avez une relation directe avec les professeurs, vous pouvez trier par le nombre de professeurs
            // Sinon, vous devrez peut-être ajouter une jointure et utiliser COUNT()
            $queryBuilder->orderBy('e.nb_professeur', 'ASC');
            break;
        default:
            $queryBuilder->orderBy('e.nom', 'ASC');
    }

    return $queryBuilder->getQuery()->getResult();
}

}
