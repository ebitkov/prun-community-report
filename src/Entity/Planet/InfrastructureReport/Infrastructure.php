<?php

namespace App\Entity\Planet\InfrastructureReport;

use App\Entity\Planet\InfrastructureReport;
use App\Repository\Planet\InfrastructureReport\InfrastructureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InfrastructureRepository::class)]
class Infrastructure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $ticker = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $level = null;

    #[ORM\Column]
    private ?int $activeLevel = null;

    #[ORM\Column(length: 255)]
    private ?int $currentLevel = null;

    #[ORM\ManyToOne(inversedBy: 'infrastructures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?InfrastructureReport $report = null;

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

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): static
    {
        $this->ticker = $ticker;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getActiveLevel(): ?int
    {
        return $this->activeLevel;
    }

    public function setActiveLevel(int $activeLevel): static
    {
        $this->activeLevel = $activeLevel;

        return $this;
    }

    public function getCurrentLevel(): ?int
    {
        return $this->currentLevel;
    }

    public function setCurrentLevel(int $currentLevel): static
    {
        $this->currentLevel = $currentLevel;

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
