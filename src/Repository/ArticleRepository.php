<?php

namespace App\Repository;

use App\Entity\Article;
use App\Entity\ArticleSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Recupérer des articles avec recherches
     *
     * @return Article[]
     */
  public function findSearch(ArticleSearch $search): array
  {
    $query = $this
    ->createQueryBuilder('a')
    ->select('c', 'a')
    ->join('a.categories', 'c');
    if (!empty($search->q)) {
        $query = $query
            ->andWhere('a.author LIKE :q')
            ->setParameter('q', "%{$search->q}%");
    }
    if (!empty($search->categories)) {
        $query = $query
            ->andWhere('c.id IN (:categories)')
            ->setParameter('categories', $search->categories);
    }
//   return $this->paginator->paginate(
//             $query,
//             $search->page,
//             9
//         );


      return $this->findAll();
  }
      

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}