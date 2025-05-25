<?php

namespace App\Entity\Planet\PopulationReport;

use App\Entity\Planet\PopulationReport;
use App\Repository\Planet\PopulationReport\WorkerReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkerReportRepository::class)]
class WorkerReport
{
    public const TYPE_PIONEERS = 'pioneers';
    public const TYPE_SETTLERS = 'settlers';
    public const TYPE_TECHNICIANS = 'technicians';
    public const TYPE_ENGINEERS = 'engineers';
    public const TYPE_SCIENTISTS = 'scientists';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $population = null;

    #[ORM\Column]
    private ?int $difference = null;

    #[ORM\Column]
    private ?float $averageHappiness = null;

    #[ORM\Column]
    private ?float $unemploymentRate = null;

    #[ORM\Column]
    private ?int $openJobs = null;

    #[ORM\ManyToOne(inversedBy: 'workerReports')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PopulationReport $populationReport = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): static
    {
        $this->population = $population;

        return $this;
    }

    public function getDifference(): ?int
    {
        return $this->difference;
    }

    public function setDifference(int $difference): static
    {
        $this->difference = $difference;

        return $this;
    }

    public function getAverageHappiness(): ?float
    {
        return $this->averageHappiness;
    }

    public function setAverageHappiness(float $averageHappiness): static
    {
        $this->averageHappiness = $averageHappiness;

        return $this;
    }

    public function getUnemploymentRate(): ?float
    {
        return $this->unemploymentRate;
    }

    public function setUnemploymentRate(float $unemploymentRate): static
    {
        $this->unemploymentRate = $unemploymentRate;

        return $this;
    }

    public function getOpenJobs(): ?int
    {
        return $this->openJobs;
    }

    public function setOpenJobs(int $openJobs): static
    {
        $this->openJobs = $openJobs;

        return $this;
    }

    public function getPopulationReport(): ?PopulationReport
    {
        return $this->populationReport;
    }

    public function setPopulationReport(?PopulationReport $populationReport): static
    {
        $this->populationReport = $populationReport;

        return $this;
    }
}
