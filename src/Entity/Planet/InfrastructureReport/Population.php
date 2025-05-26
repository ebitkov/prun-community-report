<?php

namespace App\Entity\Planet\InfrastructureReport;

use App\Entity\Planet\InfrastructureReport;
use App\Repository\Planet\InfrastructureReport\PopulationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PopulationRepository::class)]
class Population
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?int $difference = null;

    #[ORM\Column]
    private ?float $averageHappiness = null;

    #[ORM\Column]
    private ?float $unemploymentRate = null;

    #[ORM\Column]
    private ?int $openJobs = null;

    #[ORM\ManyToOne(inversedBy: 'population')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InfrastructureReport $report = null;

    public function __construct()
    {
    }

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

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

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

    public function getReport(): ?InfrastructureReport
    {
        return $this->report;
    }

    public function setReport(?InfrastructureReport $report): static
    {
        $this->report = $report;

        return $this;
    }
}
