<?php

namespace App\Entity\Material;

use App\Entity\Material;
use App\Repository\Material\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $fioId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Material>
     */
    #[ORM\OneToMany(targetEntity: Material::class, mappedBy: 'category')]
    private Collection $materials;

    public function __construct()
    {
        $this->materials = new ArrayCollection();
    }


    public function getSlug(): string
    {
        return u($this->name)->kebab();
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

    /**
     * @return Collection<int, Material>
     */
    public function getMaterials(): Collection
    {
        return $this->materials;
    }

    public function addMaterial(Material $material): static
    {
        if (!$this->materials->contains($material)) {
            $this->materials->add($material);
            $material->setCategory($this);
        }

        return $this;
    }

    public function removeMaterial(Material $material): static
    {
        if ($this->materials->removeElement($material)) {
            // set the owning side to null (unless already changed)
            if ($material->getCategory() === $this) {
                $material->setCategory(null);
            }
        }

        return $this;
    }
}
