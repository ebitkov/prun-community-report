<?php

namespace App\Entity;

use App\Entity\Workforce\Need;
use App\Repository\WorkforceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkforceRepository::class)]
class Workforce
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    /**
     * @var Collection<int, Need>
     */
    #[ORM\OneToMany(targetEntity: Need::class, mappedBy: 'workforce', cascade: ['persist', 'remove'])]
    private Collection $needs;

    public function __construct()
    {
        $this->needs = new ArrayCollection();
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

    /**
     * @return Collection<int, Need>
     */
    public function getNeeds(): Collection
    {
        return $this->needs;
    }

    public function addNeed(Need $need): static
    {
        if (!$this->needs->contains($need)) {
            $this->needs->add($need);
            $need->setWorkforce($this);
        }

        return $this;
    }

    public function removeNeed(Need $need): static
    {
        if ($this->needs->removeElement($need)) {
            // set the owning side to null (unless already changed)
            if ($need->getWorkforce() === $this) {
                $need->setWorkforce(null);
            }
        }

        return $this;
    }

    public function clearNeeds(): static
    {
        foreach ($this->getNeeds() as $need) {
            $this->removeNeed($need);
        }

        return $this;
    }
}
