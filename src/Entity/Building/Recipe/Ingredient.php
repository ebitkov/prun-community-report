<?php

namespace App\Entity\Building\Recipe;

use App\Entity\Building\Recipe;
use App\Entity\Material;
use App\Repository\Building\Recipe\IngredientRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IngredientRepository::class)]
#[ORM\Table(name: 'building_recipe_ingredient')]
class Ingredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Material $material = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\ManyToOne(inversedBy: 'inputs')]
    private ?Recipe $recipeInput = null;

    #[ORM\ManyToOne(inversedBy: 'outputs')]
    private ?Recipe $recipeOutput = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaterial(): ?Material
    {
        return $this->material;
    }

    public function setMaterial(?Material $material): static
    {
        $this->material = $material;

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

    public function getRecipeInput(): ?Recipe
    {
        return $this->recipeInput;
    }

    public function setRecipeInput(?Recipe $recipeInput): static
    {
        $this->recipeInput = $recipeInput;

        return $this;
    }

    public function getRecipeOutput(): ?Recipe
    {
        return $this->recipeOutput;
    }

    public function setRecipeOutput(?Recipe $recipeOutput): static
    {
        $this->recipeOutput = $recipeOutput;

        return $this;
    }
}
