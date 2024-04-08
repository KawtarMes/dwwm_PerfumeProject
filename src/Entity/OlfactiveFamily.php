<?php

namespace App\Entity;

use App\Repository\OlfactiveFamilyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OlfactiveFamilyRepository::class)]
class OlfactiveFamily
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $familyName = null;

    #[ORM\Column(length: 255)]
    private ?string $familyDescription = null;

    #[ORM\OneToMany(targetEntity: Perfume::class, mappedBy: 'olfactive_family_id')]
    private Collection $perfumes;

    #[ORM\ManyToMany(targetEntity: Notes::class, inversedBy: 'olfactive_family_id')]
    private Collection $notes;

    #[ORM\OneToMany(targetEntity: Favorites::class, mappedBy: 'olfactive_family')]
    private Collection $favorites;

    public function __construct()
    {
        $this->perfumes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFamilyName(): ?string
    {
        return $this->familyName;
    }

    public function setFamilyName(string $familyName): static
    {
        $this->familyName = $familyName;

        return $this;
    }

    public function getFamilyDescription(): ?string
    {
        return $this->familyDescription;
    }

    public function setFamilyDescription(string $familyDescription): static
    {
        $this->familyDescription = $familyDescription;

        return $this;
    }

    /**
     * @return Collection<int, Perfume>
     */
    public function getPerfumes(): Collection
    {
        return $this->perfumes;
    }

    public function addPerfume(Perfume $perfume): static
    {
        if (!$this->perfumes->contains($perfume)) {
            $this->perfumes->add($perfume);
            $perfume->setOlfactiveFamilyId($this);
        }

        return $this;
    }

    public function removePerfume(Perfume $perfume): static
    {
        if ($this->perfumes->removeElement($perfume)) {
            // set the owning side to null (unless already changed)
            if ($perfume->getOlfactiveFamilyId() === $this) {
                $perfume->setOlfactiveFamilyId(null);
            }
        }

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
            $note->addOlfactiveFamilyId($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): static
    {
        if ($this->notes->removeElement($note)) {
            $note->removeOlfactiveFamilyId($this);
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
            $favorite->setOlfactiveFamily($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getOlfactiveFamily() === $this) {
                $favorite->setOlfactiveFamily(null);
            }
        }

        return $this;
    }
}
