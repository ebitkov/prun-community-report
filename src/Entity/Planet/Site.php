<?php

namespace App\Entity\Planet;

use App\Entity\Company;
use App\Entity\Planet;
use App\Repository\Planet\SiteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SiteRepository::class)]
class Site
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $plotId = null;

    #[ORM\Column(length: 255)]
    private ?string $siteId = null;

    #[ORM\Column]
    private ?int $plotNumber = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Planet $planet = null;

    #[ORM\ManyToOne(inversedBy: 'sites')]
    private ?Company $owner = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlotId(): ?string
    {
        return $this->plotId;
    }

    public function setPlotId(string $plotId): static
    {
        $this->plotId = $plotId;

        return $this;
    }

    public function getSiteId(): ?string
    {
        return $this->siteId;
    }

    public function setSiteId(string $siteId): static
    {
        $this->siteId = $siteId;

        return $this;
    }

    public function getPlotNumber(): ?int
    {
        return $this->plotNumber;
    }

    public function setPlotNumber(int $plotNumber): static
    {
        $this->plotNumber = $plotNumber;

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

    public function getOwner(): ?Company
    {
        return $this->owner;
    }

    public function setOwner(?Company $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
