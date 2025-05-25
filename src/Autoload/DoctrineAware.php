<?php

namespace App\Autoload;

use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Contracts\Service\Attribute\Required;

trait DoctrineAware
{
    private ManagerRegistry $doctrine;


    #[Required]
    public function setManagerRegistry(ManagerRegistry $managerRegistry): void
    {
        $this->doctrine = $managerRegistry;
    }

    /**
     * @template TEntity of object
     * @param class-string<TEntity> $entityFqcn
     * @return ObjectRepository<TEntity>
     */
    private function getRepository(string $entityFqcn): ObjectRepository
    {
        return $this->doctrine->getRepository($entityFqcn);
    }

    private function findEntityBy(string $entityFqcn, array $criteria = []): ?object
    {
        return $this->getRepository($entityFqcn)->findOneBy($criteria);
    }

    private function persistEntity(object $entity): void
    {
        $this->doctrine->getManagerForClass(get_class($entity))->persist($entity);
    }

    private function flushEntities(): void
    {
        foreach ($this->doctrine->getManagers() as $manager) {
            $manager->flush();
        }
    }

    private function removeEntity(object $entity): void
    {
        $this->doctrine->getManagerForClass(get_class($entity))->remove($entity);
    }
}