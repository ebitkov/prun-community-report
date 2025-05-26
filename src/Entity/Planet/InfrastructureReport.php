<?php

namespace App\Entity\Planet;

use App\Entity\Planet;
use App\Entity\Planet\InfrastructureReport\Infrastructure;
use App\Entity\Planet\InfrastructureReport\Population;
use App\Repository\Planet\InfrastructureReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfrastructureReportRepository::class)]
class InfrastructureReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $simulationPeriod = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $startedAt = null;

    #[ORM\Column]
    private ?bool $isExplorersGraceEnabled = null;

    /**
     * @var Collection<int, Infrastructure>
     */
    #[ORM\OneToMany(targetEntity: Infrastructure::class, mappedBy: 'report', orphanRemoval: true)]
    private Collection $infrastructures;

    /**
     * @var Collection<int, Population>
     */
    #[ORM\OneToMany(targetEntity: Population::class, mappedBy: 'report', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $population;

    #[ORM\ManyToOne(inversedBy: 'populationReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    public function __construct()
    {
        $this->infrastructures = new ArrayCollection();
        $this->population = new ArrayCollection();
    }


    public function getPopulationByType(string $type): Population
    {
        $population = $this->population
            ->filter(function (Population $population) use ($type): bool {
                return $population->getType() == $type;
            })
            ->first();
        if (!$population) {
            $population = (new Population())->setType($type)->setReport($this);
        }
        return $population;
    }

    public function getPioneers(): Population
    {
        return $this->getPopulationByType('pioneers');
    }

    public function getSettlers(): Population
    {
        return $this->getPopulationByType('settlers');
    }

    public function getTechnicians(): Population
    {
        return $this->getPopulationByType('technicians');
    }

    public function getEngineers(): Population
    {
        return $this->getPopulationByType('engineers');
    }

    public function getScientists(): Population
    {
        return $this->getPopulationByType('scientists');
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSimulationPeriod(): ?int
    {
        return $this->simulationPeriod;
    }

    public function setSimulationPeriod(int $simulationPeriod): static
    {
        $this->simulationPeriod = $simulationPeriod;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeImmutable $startedAt): static
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function isExplorersGraceEnabled(): ?bool
    {
        return $this->isExplorersGraceEnabled;
    }

    public function setIsExplorersGraceEnabled(bool $isExplorersGraceEnabled): static
    {
        $this->isExplorersGraceEnabled = $isExplorersGraceEnabled;

        return $this;
    }

    /**
     * @return Collection<int, Infrastructure>
     */
    public function getInfrastructures(): Collection
    {
        return $this->infrastructures;
    }

    public function addInfrastructure(Infrastructure $infrastructure): static
    {
        if (!$this->infrastructures->contains($infrastructure)) {
            $this->infrastructures->add($infrastructure);
            $infrastructure->setReport($this);
        }

        return $this;
    }

    public function removeInfrastructure(Infrastructure $infrastructure): static
    {
        if ($this->infrastructures->removeElement($infrastructure)) {
            // set the owning side to null (unless already changed)
            if ($infrastructure->getReport() === $this) {
                $infrastructure->setReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Population>
     */
    public function getPopulation(): Collection
    {
        return $this->population;
    }

    public function addPopulation(Population $population): static
    {
        if (!$this->population->contains($population)) {
            $this->population->add($population);
            $population->setReport($this);
        }

        return $this;
    }

    public function removePopulation(Population $population): static
    {
        if ($this->population->removeElement($population)) {
            // set the owning side to null (unless already changed)
            if ($population->getReport() === $this) {
                $population->setReport(null);
            }
        }

        return $this;
    }

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): static
    {
        $this->planet = $planet;

        return $this;
    }
}
