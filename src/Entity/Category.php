<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Research::class, mappedBy="category", orphanRemoval=true)
     */
    private $research;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->research = new ArrayCollection();
    }

    public function __toString() {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Research[]
     */
    public function getResearch(): Collection
    {
        return $this->research;
    }

    public function addResearch(Research $research): self
    {
        if (!$this->research->contains($research)) {
            $this->research[] = $research;
            $research->setCategory($this);
        }

        return $this;
    }

    public function removeResearch(Research $research): self
    {
        if ($this->research->removeElement($research)) {
            // set the owning side to null (unless already changed)
            if ($research->getCategory() === $this) {
                $research->setCategory(null);
            }
        }

        return $this;
    }
}
