<?php

namespace App\Entity\Workforce;

use App\Entity\Material;
use App\Entity\Workforce;
use App\Repository\Workforce\NeedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NeedRepository::class)]
#[ORM\Table(name: 'workforce_need')]
class Need
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'needs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Workforce $workforce = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column]
    private ?float $amount = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorkforce(): ?Workforce
    {
        return $this->workforce;
    }

    public function setWorkforce(?Workforce $workforce): static
    {
        $this->workforce = $workforce;

        return $this;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
