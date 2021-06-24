<?php

namespace App\Entity;

use App\Repository\BusinessIdeaRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BusinessIdeaRepository::class)
 */
class BusinessIdea
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="businessIdeas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=Prototype::class, inversedBy="businessIdeas")
     */
    private $prototype;

    /**
     * @ORM\OneToMany(targetEntity=BusinessProject::class, mappedBy="businessIdea")
     */
    private $businessProjects;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->businessProjects = new ArrayCollection();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

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

    public function getPrototype(): ?Prototype
    {
        return $this->prototype;
    }

    public function setPrototype(?Prototype $prototype): self
    {
        $this->prototype = $prototype;

        return $this;
    }

    /**
     * @return Collection|BusinessProject[]
     */
    public function getBusinessProjects(): Collection
    {
        return $this->businessProjects;
    }

    public function addBusinessProject(BusinessProject $businessProject): self
    {
        if (!$this->businessProjects->contains($businessProject)) {
            $this->businessProjects[] = $businessProject;
            $businessProject->setBusinessIdea($this);
        }

        return $this;
    }

    public function removeBusinessProject(BusinessProject $businessProject): self
    {
        if ($this->businessProjects->removeElement($businessProject)) {
            // set the owning side to null (unless already changed)
            if ($businessProject->getBusinessIdea() === $this) {
                $businessProject->setBusinessIdea(null);
            }
        }

        return $this;
    }
}
