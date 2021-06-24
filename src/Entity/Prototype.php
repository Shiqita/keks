<?php

namespace App\Entity;

use App\Repository\PrototypeRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PrototypeRepository::class)
 */
class Prototype
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
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity=Research::class, inversedBy="prototypes")
     */
    private $research;

    /**
     * @ORM\OneToMany(targetEntity=BusinessIdea::class, mappedBy="prototype")
     */
    private $businessIdeas;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->businessIdeas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getResearch(): ?Research
    {
        return $this->research;
    }

    public function setResearch(?Research $research): self
    {
        $this->research = $research;

        return $this;
    }

    /**
     * @return Collection|BusinessIdea[]
     */
    public function getBusinessIdeas(): Collection
    {
        return $this->businessIdeas;
    }

    public function addBusinessIdea(BusinessIdea $businessIdea): self
    {
        if (!$this->businessIdeas->contains($businessIdea)) {
            $this->businessIdeas[] = $businessIdea;
            $businessIdea->setPrototype($this);
        }

        return $this;
    }

    public function removeBusinessIdea(BusinessIdea $businessIdea): self
    {
        if ($this->businessIdeas->removeElement($businessIdea)) {
            // set the owning side to null (unless already changed)
            if ($businessIdea->getPrototype() === $this) {
                $businessIdea->setPrototype(null);
            }
        }

        return $this;
    }
}
