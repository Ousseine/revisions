<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    // return all posts
    public function findAllByDesc()
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    // Search
    public function findSearch(SearchData $search): array
    {
        $searchTerms = $this->extractSearchTerms($search);

        if (0 === count($searchTerms)) {
            return [];
        }

        $qb = $this->createQueryBuilder('p')
            ->select('p', 'c', 't')
            ->innerJoin('p.tags', 't')
            ->leftJoin('p.categories', 'c')
        ;


        foreach ($searchTerms as $key => $term) {
            $qb
                ->orWhere('p.title LIKE :t_'.$key)
                ->orWhere('p.content LIKE :t_'.$key)
                ->setParameter('t_'.$key, '%'. $term .'%')
            ;
        }

        if(!empty($search->categories)) {
            $qb->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }

        return $qb
            ->orderBy('p.published_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchTerms): array
    {
        $searchQuery = trim(preg_replace('/[[:space:]]+/', ' ', $searchTerms));
        $terms = array_unique(explode(' ', $searchQuery));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }


}
