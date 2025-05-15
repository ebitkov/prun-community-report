<?php

namespace App\Entity\Building;

use App\Entity\Building;
use App\Entity\Building\Recipe\Ingredient;
use App\Repository\Building\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ORM\Table(name: 'building_recipe')]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $standardName = null;

    #[ORM\Column]
    private ?int $durationMs = null;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\OneToMany(targetEntity: Ingredient::class, mappedBy: 'recipeInput', cascade: ['persist'], orphanRemoval: true)]
    private Collection $inputs;

    /**
     * @var Collection<int, Ingredient>
     */
    #[ORM\OneToMany(targetEntity: Ingredient::class, mappedBy: 'recipeOutput', cascade: ['persist'], orphanRemoval: true)]
    private Collection $outputs;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Building $building = null;

    public function __construct()
    {
        $this->inputs = new ArrayCollection();
        $this->outputs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getInputs(): Collection
    {
        return $this->inputs;
    }

    public function addInput(Ingredient $input): static
    {
        if (!$this->inputs->contains($input)) {
            $this->inputs->add($input);
            $input->setRecipeInput($this);
        }

        return $this;
    }

    public function removeInput(Ingredient $input): static
    {
        if ($this->inputs->removeElement($input)) {
            // set the owning side to null (unless already changed)
            if ($input->getRecipeInput() === $this) {
                $input->setRecipeInput(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ingredient>
     */
    public function getOutputs(): Collection
    {
        return $this->outputs;
    }

    public function addOutput(Ingredient $output): static
    {
        if (!$this->outputs->contains($output)) {
            $this->outputs->add($output);
            $output->setRecipeOutput($this);
        }

        return $this;
    }

    public function removeOutput(Ingredient $output): static
    {
        if ($this->outputs->removeElement($output)) {
            // set the owning side to null (unless already changed)
            if ($output->getRecipeOutput() === $this) {
                $output->setRecipeOutput(null);
            }
        }

        return $this;
    }

    public function getDurationMs(): ?int
    {
        return $this->durationMs;
    }

    public function setDurationMs(int $durationMs): static
    {
        $this->durationMs = $durationMs;

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

    public function getStandardName(): ?string
    {
        return $this->standardName;
    }

    public function setStandardName(string $standardName): static
    {
        $this->standardName = $standardName;

        return $this;
    }

    public function getBuilding(): ?Building
    {
        return $this->building;
    }

    public function setBuilding(?Building $building): static
    {
        $this->building = $building;

        return $this;
    }
}
