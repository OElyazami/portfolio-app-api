<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function findByCriteria(
        array $criteria = [],
        ?int $categoryId = null,
        ?int $skillId = null,
        ?string $search = null,
        int $page = 1,
        int $limit = 10
    ): Paginator {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.categories', 'c')
            ->leftJoin('p.skills', 's')
            ->leftJoin('p.user', 'u');
        
        // Apply basic criteria
        foreach ($criteria as $field => $value) {
            $qb->andWhere("p.{$field} = :{$field}")
               ->setParameter($field, $value);
        }
        
        // Filter by category
        if ($categoryId) {
            $qb->andWhere('c.id = :categoryId')
               ->setParameter('categoryId', $categoryId);
        }
        
        // Filter by skill
        if ($skillId) {
            $qb->andWhere('s.id = :skillId')
               ->setParameter('skillId', $skillId);
        }
        
        // Search
        if ($search) {
            $qb->andWhere('p.title LIKE :search OR p.description LIKE :search')
               ->setParameter('search', "%{$search}%");
        }
        
        // Order and paginate
        $qb->orderBy('p.years', 'DESC')
           ->setFirstResult(($page - 1) * $limit)
           ->setMaxResults($limit);
        
        return new Paginator($qb->getQuery());
    }

    public function search(string $query, int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.title LIKE :query')
            ->orWhere('p.description LIKE :query')
            ->orWhere('p.shortDescription LIKE :query')
            ->setParameter('query', "%{$query}%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAverageYears(): float
    {
        $result = $this->createQueryBuilder('p')
            ->select('AVG(p.years) as average_years')
            ->getQuery()
            ->getSingleScalarResult();
        
        return round($result, 1);
    }

    public function getProjectsByYear(): array
    {
        return $this->createQueryBuilder('p')
            ->select('p.years as year, COUNT(p.id) as count')
            ->groupBy('p.years')
            ->orderBy('p.years', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getTopCategories(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('c.id, c.name, COUNT(p.id) as project_count')
            ->leftJoin('p.categories', 'c')
            ->groupBy('c.id')
            ->orderBy('project_count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getTopSkills(int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->select('s.id, s.name, COUNT(p.id) as project_count')
            ->leftJoin('p.skills', 's')
            ->groupBy('s.id')
            ->orderBy('project_count', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}