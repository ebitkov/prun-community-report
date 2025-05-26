<?php

namespace App\Entity;

use App\Entity\Planet\CoGCProgram;
use App\Entity\Planet\InfrastructureReport;
use App\Entity\Planet\Resource;
use App\Entity\Planet\Site;
use App\Repository\PlanetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanetRepository::class)]
class Planet
{
    public const INFRASTRUCTURE_ADMINISTRATION_CENTER = 'ADMINISTRATION_CENTER';
    public const INFRASTRUCTURE_WAREHOUSE = 'WAREHOUSE';
    public const INFRASTRUCTURE_SHIPYARD = 'SHIPYARD';
    public const INFRASTRUCTURE_CHAMBER_OF_GLOBAL_COMMERCE = 'CHAMBER_OF_GLOBAL_COMMERCE';
    public const INFRASTRUCTURE_LOCAL_MARKET = 'LOCAL_MARKET';


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $fioId = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $naturalId = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $gravity = null;

    #[ORM\Column(length: 255)]
    private ?float $pressure = null;

    #[ORM\Column]
    private ?float $temperature = null;

    #[ORM\Column]
    private ?bool $hasSurface = null;

    #[ORM\Column]
    private ?float $fertility = null;

    /**
     * @var Collection<int, Resource>
     */
    #[ORM\OneToMany(targetEntity: Resource::class, mappedBy: 'planet', cascade: [
        'persist',
        'remove'
    ], orphanRemoval: true)]
    private Collection $resources;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, nullable: true)]
    private ?array $planetaryInfrastructure = [];

    /**
     * @var Collection<int, Site>
     */
    #[ORM\OneToMany(targetEntity: Site::class, mappedBy: 'planet', orphanRemoval: true)]
    private Collection $sites;

    #[ORM\OneToOne(inversedBy: 'planet', cascade: ['persist', 'remove'])]
    private ?CoGCProgram $cogcProgram = null;

    #[ORM\ManyToOne(inversedBy: 'planets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?System $system = null;

    /**
     * @var Collection<int, InfrastructureReport>
     */
    #[ORM\OneToMany(targetEntity: InfrastructureReport::class, mappedBy: 'planet', orphanRemoval: true)]
    private Collection $populationReports;

    #[ORM\Column(nullable: true)]
    private ?int $jumpsToAnt = null;


    public function __construct()
    {
        $this->resources = new ArrayCollection();
        $this->sites = new ArrayCollection();
        $this->populationReports = new ArrayCollection();
    }


    public function hasChamberOfGlobalCommerce(): bool
    {
        return in_array(self::INFRASTRUCTURE_CHAMBER_OF_GLOBAL_COMMERCE, $this->planetaryInfrastructure);
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

    public function getNaturalId(): ?string
    {
        return $this->naturalId;
    }

    public function setNaturalId(string $naturalId): static
    {
        $this->naturalId = $naturalId;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getGravity(): ?float
    {
        return $this->gravity;
    }

    public function setGravity(float $gravity): static
    {
        $this->gravity = $gravity;

        return $this;
    }

    public function getPressure(): ?float
    {
        return $this->pressure;
    }

    public function setPressure(float $pressure): static
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getTemperature(): ?float
    {
        return $this->temperature;
    }

    public function setTemperature(float $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function hasSurface(): ?bool
    {
        return $this->hasSurface;
    }

    public function setHasSurface(bool $hasSurface): static
    {
        $this->hasSurface = $hasSurface;

        return $this;
    }

    public function getFertility(): ?float
    {
        return $this->fertility;
    }

    public function setFertility(float $fertility): static
    {
        $this->fertility = $fertility;

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResources(): Collection
    {
        return $this->resources;
    }

    public function addResource(Resource $resource): static
    {
        if (!$this->resources->contains($resource)) {
            $this->resources->add($resource);
            $resource->setPlanet($this);
        }

        return $this;
    }

    public function removeResource(Resource $resource): static
    {
        if ($this->resources->removeElement($resource)) {
            // set the owning side to null (unless already changed)
            if ($resource->getPlanet() === $this) {
                $resource->setPlanet(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Site>
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    public function addSite(Site $site): static
    {
        if (!$this->sites->contains($site)) {
            $this->sites->add($site);
            $site->setPlanet($this);
        }

        return $this;
    }

    public function removeSite(Site $site): static
    {
        if ($this->sites->removeElement($site)) {
            // set the owning side to null (unless already changed)
            if ($site->getPlanet() === $this) {
                $site->setPlanet(null);
            }
        }

        return $this;
    }

    public function getPlanetaryInfrastructure(): array
    {
        return $this->planetaryInfrastructure;
    }

    public function setPlanetaryInfrastructure(array $planetaryInfrastructure): static
    {
        $this->planetaryInfrastructure = $planetaryInfrastructure;

        return $this;
    }

    public function getCogcProgram(): ?CoGCProgram
    {
        return $this->cogcProgram;
    }

    public function setCogcProgram(?CoGCProgram $cogcProgram): static
    {
        $this->cogcProgram = $cogcProgram;

        return $this;
    }

    public function getSystem(): ?System
    {
        return $this->system;
    }

    public function setSystem(?System $system): static
    {
        $this->system = $system;

        return $this;
    }

    /**
     * @return Collection<int, InfrastructureReport>
     */
    public function getPopulationReports(): Collection
    {
        return $this->populationReports;
    }

    public function addPopulationReport(InfrastructureReport $populationReport): static
    {
        if (!$this->populationReports->contains($populationReport)) {
            $this->populationReports->add($populationReport);
            $populationReport->setPlanet($this);
        }

        return $this;
    }

    public function removePopulationReport(InfrastructureReport $populationReport): static
    {
        if ($this->populationReports->removeElement($populationReport)) {
            // set the owning side to null (unless already changed)
            if ($populationReport->getPlanet() === $this) {
                $populationReport->setPlanet(null);
            }
        }

        return $this;
    }

    public function getJumpsToAnt(): ?int
    {
        return $this->jumpsToAnt;
    }

    public function setJumpsToAnt(?int $jumpsToAnt): static
    {
        $this->jumpsToAnt = $jumpsToAnt;

        return $this;
    }
}
