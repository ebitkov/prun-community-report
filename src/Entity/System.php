<?php

namespace App\Entity;

use App\Repository\SystemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SystemRepository::class)]
class System
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fioId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?string $naturalId = null;

    /**
     * @var Collection<int, Planet>
     */
    #[ORM\OneToMany(targetEntity: Planet::class, mappedBy: 'System')]
    private Collection $planets;

    public function __construct()
    {
        $this->planets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFioId(): ?string
    {
        return $this->fioId;
    }

    public function setFioId(string $fioId): static
    {
        $this->fioId = $fioId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getNaturalId(): ?string
    {
        return $this->naturalId;
    }

    public function setNaturalId(string $naturalId): static
    {
        $this->naturalId = $naturalId;

        return $this;
    }

    /**
     * @return Collection<int, Planet>
     */
    public function getPlanets(): Collection
    {
        return $this->planets;
    }

    public function addPlanet(Planet $planet): static
    {
        if (!$this->planets->contains($planet)) {
            $this->planets->add($planet);
            $planet->setSystem($this);
        }

        return $this;
    }

    public function removePlanet(Planet $planet): static
    {
        if ($this->planets->removeElement($planet)) {
            // set the owning side to null (unless already changed)
            if ($planet->getSystem() === $this) {
                $planet->setSystem(null);
            }
        }

        return $this;
    }
}
