<?php

namespace App\Entity;

use App\Entity\Building\Cost;
use App\Entity\Building\Recipe;
use App\Repository\BuildingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuildingRepository::class)]
class Building
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fioId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $ticker = null;

    #[ORM\Column]
    private ?int $requiredPioneers = null;

    #[ORM\Column]
    private ?int $requiredSettlers = null;

    #[ORM\Column]
    private ?int $requiredTechnicians = null;

    #[ORM\Column]
    private ?int $requiredEngineers = null;

    #[ORM\Column]
    private ?int $requiredScientists = null;

    #[ORM\Column]
    private ?int $areaCost = null;

    /**
     * @var Collection<int, Cost>
     */
    #[ORM\OneToMany(targetEntity: Cost::class, mappedBy: 'building', orphanRemoval: true)]
    private Collection $constructionCosts;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'building', orphanRemoval: true)]
    private Collection $recipes;

    #[ORM\ManyToOne(inversedBy: 'buildings')]
    private ?Expertise $expertise = null;

    public function __construct()
    {
        $this->constructionCosts = new ArrayCollection();
        $this->recipes = new ArrayCollection();
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

    public function getTicker(): ?string
    {
        return $this->ticker;
    }

    public function setTicker(string $ticker): static
    {
        $this->ticker = $ticker;

        return $this;
    }

    public function getRequiredPioneers(): ?int
    {
        return $this->requiredPioneers;
    }

    public function setRequiredPioneers(int $requiredPioneers): static
    {
        $this->requiredPioneers = $requiredPioneers;

        return $this;
    }

    public function getRequiredSettlers(): ?int
    {
        return $this->requiredSettlers;
    }

    public function setRequiredSettlers(int $requiredSettlers): static
    {
        $this->requiredSettlers = $requiredSettlers;

        return $this;
    }

    public function getRequiredTechnicians(): ?int
    {
        return $this->requiredTechnicians;
    }

    public function setRequiredTechnicians(int $requiredTechnicians): static
    {
        $this->requiredTechnicians = $requiredTechnicians;

        return $this;
    }

    public function getRequiredEngineers(): ?int
    {
        return $this->requiredEngineers;
    }

    public function setRequiredEngineers(int $requiredEngineers): static
    {
        $this->requiredEngineers = $requiredEngineers;

        return $this;
    }

    public function getRequiredScientists(): ?int
    {
        return $this->requiredScientists;
    }

    public function setRequiredScientists(int $requiredScientists): static
    {
        $this->requiredScientists = $requiredScientists;

        return $this;
    }

    public function getAreaCost(): ?int
    {
        return $this->areaCost;
    }

    public function setAreaCost(int $areaCost): static
    {
        $this->areaCost = $areaCost;

        return $this;
    }

    /**
     * @return Collection<int, Cost>
     */
    public function getConstructionCosts(): Collection
    {
        return $this->constructionCosts;
    }

    public function addConstructionCost(Cost $constructionCost): static
    {
        if (!$this->constructionCosts->contains($constructionCost)) {
            $this->constructionCosts->add($constructionCost);
            $constructionCost->setBuilding($this);
        }

        return $this;
    }

    public function removeConstructionCost(Cost $constructionCost): static
    {
        if ($this->constructionCosts->removeElement($constructionCost)) {
            // set the owning side to null (unless already changed)
            if ($constructionCost->getBuilding() === $this) {
                $constructionCost->setBuilding(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setBuilding($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getBuilding() === $this) {
                $recipe->setBuilding(null);
            }
        }

        return $this;
    }

    public function getExpertise(): ?Expertise
    {
        return $this->expertise;
    }

    public function setExpertise(?Expertise $expertise): static
    {
        $this->expertise = $expertise;

        return $this;
    }
}
