<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(['email'], message:'Email existant')]// unicité de l'email pour UN seul user
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nickname = null;

    #[ORM\Column(length: 50)]
    private ?string $firstname = null;

    #[ORM\Column(length: 90)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $active = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private ?array $roles = ['ROLE_USER'];

    #[ORM\ManyToOne(inversedBy: 'user_id')]
    private ?Rating $rating = null;

    #[ORM\OneToMany(targetEntity: OrderPurchase::class, mappedBy: 'user')]
    private Collection $orderPurchases;

    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'user')]
    private Collection $favorites;

    public function __construct()
    {
        $this->orderPurchases = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /* * Get the value of roles
        */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * Set the value of roles.
     *
     * @return self
     */
    public function setRoles(array $role)
    {
        $this->roles = $role;

        return $this;
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(?Rating $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @return Collection<int, OrderPurchase>
     */
    public function getOrderPurchases(): Collection
    {
        return $this->orderPurchases;
    }

    public function addOrderPurchase(OrderPurchase $orderPurchase): static
    {
        if (!$this->orderPurchases->contains($orderPurchase)) {
            $this->orderPurchases->add($orderPurchase);
            $orderPurchase->setUser($this);
        }

        return $this;
    }

    public function removeOrderPurchase(OrderPurchase $orderPurchase): static
    {
        if ($this->orderPurchases->removeElement($orderPurchase)) {
            // set the owning side to null (unless already changed)
            if ($orderPurchase->getUser() === $this) {
                $orderPurchase->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorites>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorites $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setUser($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getUser() === $this) {
                $favorite->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials()
    {
        //TO DO
    }
    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
