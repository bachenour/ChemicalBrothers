<?php

namespace App\Repository;

use App\Entity\FDS;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FDS>
 *
 * @method FDS|null find($id, $lockMode = null, $lockVersion = null)
 * @method FDS|null findOneBy(array $criteria, array $orderBy = null)
 * @method FDS[]    findAll()
 * @method FDS[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FDSRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FDS::class);
    }

//    /**
//     * @return FDS[] Returns an array of FDS objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FDS
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
