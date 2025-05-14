<?php

namespace App\Repository\Material;

use App\Entity\Material\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    public function persist(Category $category, bool $flush = false): void
    {
        $_em = $this->getEntityManager();
        $_em->persist($category);
        if ($flush) {
            $_em->flush();
        }
    }
}
