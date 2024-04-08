<?php

namespace App\Entity;

use App\Repository\NotesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotesRepository::class)]
class Notes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $noteName = null;

    #[ORM\ManyToMany(targetEntity: Perfume::class, mappedBy: 'note_id')]
    private Collection $perfumes;

    #[ORM\ManyToMany(targetEntity: OlfactiveFamily::class, mappedBy: 'notes')]
    private Collection $olfactive_family_id;

    #[ORM\ManyToMany(targetEntity: Favorites::class, mappedBy: 'notes')]
    private Collection $favorites;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Media $media = null;

    public function __construct()
    {
        $this->perfumes = new ArrayCollection();
        $this->olfactive_family_id = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteName(): ?string
    {
        return $this->noteName;
    }

    public function setNoteName(string $noteName): static
    {
        $this->noteName = $noteName;

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
            $perfume->addNoteId($this);
        }

        return $this;
    }

    public function removePerfume(Perfume $perfume): static
    {
        if ($this->perfumes->removeElement($perfume)) {
            $perfume->removeNoteId($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, OlfactiveFamily>
     */
    public function getOlfactiveFamilyId(): Collection
    {
        return $this->olfactive_family_id;
    }

    public function addOlfactiveFamilyId(OlfactiveFamily $olfactiveFamilyId): static
    {
        if (!$this->olfactive_family_id->contains($olfactiveFamilyId)) {
            $this->olfactive_family_id->add($olfactiveFamilyId);
        }

        return $this;
    }
    

    public function removeOlfactiveFamilyId(OlfactiveFamily $olfactiveFamilyId): static
    {
        $this->olfactive_family_id->removeElement($olfactiveFamilyId);

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
            $favorite->addNote($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removeNote($this);
        }

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }
}
