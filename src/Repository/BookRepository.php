<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findAllAvailable(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.deletedAt IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * Fetch the list of books when their deleted_at value is set to null
     * @return Paginator
     */
    public function findAllAvailablePaginate(int $page, int $limit): Paginator
    {
        return new Paginator(
            $this->createQueryBuilder('b')
            ->select('b', 'a')
            ->leftJoin('b.author', 'a')
            ->where('b.deletedAt IS NULL')
            ->orderBy('b.title', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            // Indicates to Doctrine to not perform a DISTINCT on the ID
            // (default behavior of Paginator)
            ->setHint(Paginator::HINT_ENABLE_DISTINCT, false)
        );
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
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

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
