<?php

namespace App\Twig\Components;

use App\Entity\FIO\Planet;
use App\FIO\Client;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\DocBlock\Tags\PropertyWrite;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('planets')]
final class PlanetList
{
    use DefaultActionTrait;


    #[LiveProp(writable: true)]
    public ?string $search = null;


    public function __construct(
        private readonly ManagerRegistry $doctrine,
    ) {
    }


    public function getPlanets(): array
    {
        $rep = $this->doctrine->getRepository(\App\Entity\Planet::class);
        $_qb = $rep->createQueryBuilder('planet');

        if ($this->search) {
            $term = strtolower($this->search);
            $_qb->andWhere(
                $_qb->expr()->orX(
                    $_qb->expr()->like('planet.name', ':term'),
                    $_qb->expr()->like('planet.naturalId', ':term')
                )
            );
            $_qb->setParameter('term', "%$term%");
        }

        return $_qb->getQuery()->getResult();
    }
}