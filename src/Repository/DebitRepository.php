<?php

namespace App\Repository;

use App\Entity\Debit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Debit>
 *
 * @method Debit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Debit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Debit[]    findAll()
 * @method Debit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DebitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct($registry, Debit::class);
    }

    public function save(Debit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Debit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Debit[] Returns an array of Debit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @return Debit[] Returns an array of Debit objects
     */
    public function findAllByMois($mois): array
    {
        $results = $this->entityManager->createQuery('SELECT d, m 
        FROM App\Entity\Debit d 
        INNER JOIN d.id_mois m WHERE m.id=:id')

            ->setParameter('id', intval($mois));


        return $results->getResult();
    }
    /**
     * @return Debit[] Returns an array of Debit objects
     */
    public function findSumByMois($mois): array
    {
        $results = $this->entityManager->createQuery('SELECT sum(d.montant) montantTotal, d, m 
        FROM App\Entity\Debit d 
        INNER JOIN d.id_mois m WHERE m.id=:id')
            ->setParameter('id', intval($mois));

        return $results->getResult();
    }

//    public function findOneBySomeField($value): ?Debit
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


}
