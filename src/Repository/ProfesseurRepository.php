<?php

namespace App\Repository;

use App\Entity\Professeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Professeur>
 *
 * @method Professeur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Professeur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Professeur[]    findAll()
 * @method Professeur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfesseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professeur::class);
    }

    public function findAllSortedBy(string $sortBy): array
    {
        $qb = $this->createQueryBuilder('p');
        
        // Ajouter des conditions pour trier selon le critère choisi
        switch ($sortBy) {
            case 'id':
                $qb->orderBy('p.id', 'ASC');
                break;
            case 'ecole':
                $qb->leftJoin('p.ecole', 'e')->orderBy('e.nom', 'ASC');
                break;
            case 'nom':
            default:
                $qb->orderBy('p.nom', 'ASC');
                break;
        }

        return $qb->getQuery()->getResult();
    }


}
