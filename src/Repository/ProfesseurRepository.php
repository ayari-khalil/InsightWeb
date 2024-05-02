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
/**
     * Récupère tous les professeurs triés par la colonne spécifiée.
     *
     * @param string $sortBy La colonne par laquelle trier les professeurs
     * @return Professeur[] Tableau de professeurs triés
     */
    public function findAllSortedBy(string $sortBy): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        switch ($sortBy) {
            case 'nom':
                $queryBuilder->orderBy('p.nom', 'ASC');
                break;
            case 'id':
                $queryBuilder->orderBy('p.id', 'ASC');
                break;
            case 'prenom':
                 $queryBuilder->orderBy('p.prenom', 'ASC');
                break;        
            case 'ecole.nom':
                $queryBuilder->join('p.ecole', 'e')->orderBy('e.nom', 'ASC');
                break;
            default:
                $queryBuilder->orderBy('p.nom', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }

}
