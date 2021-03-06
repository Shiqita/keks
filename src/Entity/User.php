<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true , nullable=false)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="author", orphanRemoval=true)
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity=BusinessIdea::class, mappedBy="author")
     */
    private $businessIdeas;

    /**
     * @ORM\OneToMany(targetEntity=BusinessProject::class, mappedBy="author")
     */
    private $businessProjects;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->businessIdeas = new ArrayCollection();
        $this->businessProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setAuthor($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getAuthor() === $this) {
                $order->setAuthor(null);
            }
        }

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
            $businessIdea->setAuthor($this);
        }

        return $this;
    }

    public function removeBusinessIdea(BusinessIdea $businessIdea): self
    {
        if ($this->businessIdeas->removeElement($businessIdea)) {
            // set the owning side to null (unless already changed)
            if ($businessIdea->getAuthor() === $this) {
                $businessIdea->setAuthor(null);
            }
        }

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
            $businessProject->setAuthor($this);
        }

        return $this;
    }

    public function removeBusinessProject(BusinessProject $businessProject): self
    {
        if ($this->businessProjects->removeElement($businessProject)) {
            // set the owning side to null (unless already changed)
            if ($businessProject->getAuthor() === $this) {
                $businessProject->setAuthor(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
