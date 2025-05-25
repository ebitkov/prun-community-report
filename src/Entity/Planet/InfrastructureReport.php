<?php

namespace App\Entity\Planet;

use App\Entity\Planet;
use App\Repository\Planet\InfrastructureReportRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

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
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?bool $isExplorersGraceEnabled = null;

    #[ORM\ManyToOne(inversedBy: 'infrastructureReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\OneToOne(inversedBy: 'infrastructureReport', cascade: ['persist', 'remove'])]
    #[JoinColumn(nullable: false)]
    private ?PopulationReport $populationReport = null;

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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

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

    public function getPlanet(): ?Planet
    {
        return $this->planet;
    }

    public function setPlanet(?Planet $planet): static
    {
        $this->planet = $planet;

        return $this;
    }

    public function getPopulationReport(): ?PopulationReport
    {
        return $this->populationReport;
    }

    public function setPopulationReport(PopulationReport $populationReport): static
    {
        // set the owning side of the relation if necessary
        if ($populationReport->getInfrastructureReport() !== $this) {
            $populationReport->setInfrastructureReport($this);
        }

        $this->populationReport = $populationReport;

        return $this;
    }
}
