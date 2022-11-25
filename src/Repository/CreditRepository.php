<?php

namespace App\Repository;

use App\Entity\Credit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Credit>
 *
 * @method Credit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Credit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Credit[]    findAll()
 * @method Credit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreditRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry,  EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Credit::class);
    }

    public function save(Credit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Credit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Credit[] Returns an array of Credit objects
     */
    public function findAllByMois($mois): array
    {
        $results = $this->entityManager->createQuery('SELECT c, m 
        FROM App\Entity\Credit c 
        INNER JOIN c.id_mois m WHERE m.id=:id')

            ->setParameter('id', intval($mois));

        return $results->getResult();
    }
    /**
     * @return Credit[] Returns sum of Credit objects
     */
    public function findSumByMois($mois): array
    {
        $results = $this->entityManager->createQuery('SELECT sum(c.montant) montantTotal c, m 
        FROM App\Entity\Credit c 
        INNER JOIN c.id_mois m WHERE m.id=:id')

            ->setParameter('id', intval($mois));

        return $results->getResult();
    }

//    /**
//     * @return Credit[] Returns an array of Credit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Credit
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
