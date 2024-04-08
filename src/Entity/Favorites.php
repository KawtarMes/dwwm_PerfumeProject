<?php

namespace App\Entity;

use App\Repository\FavoritesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritesRepository::class)]
class Favorites
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Perfume::class, inversedBy: 'favorites')]
    private Collection $perfume_id;

    #[ORM\ManyToMany(targetEntity: Notes::class, inversedBy: 'favorites')]
    private Collection $notes;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?OlfactiveFamily $olfactive_family = null;

    public function __construct()
    {
        $this->perfume_id = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Perfume>
     */
    public function getPerfumeId(): Collection
    {
        return $this->perfume_id;
    }

    public function addPerfumeId(Perfume $perfumeId): static
    {
        if (!$this->perfume_id->contains($perfumeId)) {
            $this->perfume_id->add($perfumeId);
        }

        return $this;
    }

    public function removePerfumeId(Perfume $perfumeId): static
    {
        $this->perfume_id->removeElement($perfumeId);

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Notes $note): static
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
        }

        return $this;
    }

    public function removeNote(Notes $note): static
    {
        $this->notes->removeElement($note);

        return $this;
    }

    public function getOlfactiveFamily(): ?OlfactiveFamily
    {
        return $this->olfactive_family;
    }

    public function setOlfactiveFamily(?OlfactiveFamily $olfactive_family): static
    {
        $this->olfactive_family = $olfactive_family;

        return $this;
    }
}
