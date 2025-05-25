<?php

namespace App\Entity\Planet;

use App\Entity\Planet\PopulationReport\WorkerReport;
use App\Repository\Planet\PopulationReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PopulationReportRepository::class)]
class PopulationReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'populationReport')]
    private ?InfrastructureReport $infrastructureReport = null;

    #[ORM\Column]
    private ?float $needFulfillmentLifeSupport = null;

    #[ORM\Column]
    private ?float $needFulfillmentSafety = null;

    #[ORM\Column]
    private ?float $needFulfillmentHealth = null;

    #[ORM\Column]
    private ?float $needFulfillmentComfort = null;

    #[ORM\Column]
    private ?float $needFulfillmentCulture = null;

    #[ORM\Column]
    private ?float $needFulfillmentEducation = null;

    /**
     * @var Collection<int, WorkerReport>
     */
    #[ORM\OneToMany(targetEntity: WorkerReport::class, mappedBy: 'populationReport', orphanRemoval: true)]
    private Collection $workerReports;

    public function __construct()
    {
        $this->workerReports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInfrastructureReport(): ?InfrastructureReport
    {
        return $this->infrastructureReport;
    }

    public function setInfrastructureReport(InfrastructureReport $infrastructureReport): static
    {
        $this->infrastructureReport = $infrastructureReport;

        return $this;
    }

    public function getNeedFulfillmentLifeSupport(): ?float
    {
        return $this->needFulfillmentLifeSupport;
    }

    public function setNeedFulfillmentLifeSupport(float $needFulfillmentLifeSupport): static
    {
        $this->needFulfillmentLifeSupport = $needFulfillmentLifeSupport;

        return $this;
    }

    public function getNeedFulfillmentSafety(): ?float
    {
        return $this->needFulfillmentSafety;
    }

    public function setNeedFulfillmentSafety(float $needFulfillmentSafety): static
    {
        $this->needFulfillmentSafety = $needFulfillmentSafety;

        return $this;
    }

    public function getNeedFulfillmentHealth(): ?float
    {
        return $this->needFulfillmentHealth;
    }

    public function setNeedFulfillmentHealth(float $needFulfillmentHealth): static
    {
        $this->needFulfillmentHealth = $needFulfillmentHealth;

        return $this;
    }

    public function getNeedFulfillmentComfort(): ?float
    {
        return $this->needFulfillmentComfort;
    }

    public function setNeedFulfillmentComfort(float $needFulfillmentComfort): static
    {
        $this->needFulfillmentComfort = $needFulfillmentComfort;

        return $this;
    }

    public function getNeedFulfillmentCulture(): ?float
    {
        return $this->needFulfillmentCulture;
    }

    public function setNeedFulfillmentCulture(float $needFulfillmentCulture): static
    {
        $this->needFulfillmentCulture = $needFulfillmentCulture;

        return $this;
    }

    public function getNeedFulfillmentEducation(): ?float
    {
        return $this->needFulfillmentEducation;
    }

    public function setNeedFulfillmentEducation(float $needFulfillmentEducation): static
    {
        $this->needFulfillmentEducation = $needFulfillmentEducation;

        return $this;
    }

    /**
     * @return Collection<int, WorkerReport>
     */
    public function getWorkerReports(): Collection
    {
        return $this->workerReports;
    }

    public function addWorkerReport(WorkerReport $workerReport): static
    {
        if (!$this->workerReports->contains($workerReport)) {
            $this->workerReports->add($workerReport);
            $workerReport->setPopulationReport($this);
        }

        return $this;
    }

    public function removeWorkerReport(WorkerReport $workerReport): static
    {
        if ($this->workerReports->removeElement($workerReport)) {
            // set the owning side to null (unless already changed)
            if ($workerReport->getPopulationReport() === $this) {
                $workerReport->setPopulationReport(null);
            }
        }

        return $this;
    }
}
