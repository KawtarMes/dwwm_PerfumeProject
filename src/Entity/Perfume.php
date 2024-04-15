<?php

namespace App\Entity;

use App\Repository\PerfumeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PerfumeRepository::class)]
class Perfume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $perfume_title = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $gender = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'perfumes')]
    private ?OlfactiveFamily $olfactive_family_id = null;

    #[ORM\ManyToMany(targetEntity: Notes::class, inversedBy: 'perfumes')]
    private Collection $note_id;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'perfume')]
    private Collection $medias_id;

    #[ORM\OneToMany(targetEntity: Purchase::class, mappedBy: 'perfume')]
    private Collection $purchase;

    #[ORM\ManyToMany(targetEntity: Favorites::class, mappedBy: 'perfume_id')]
    private Collection $favorites;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $volume = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $quantity = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    public function __construct()
    {
        $this->note_id = new ArrayCollection();
        $this->medias_id = new ArrayCollection();
        $this->purchase = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerfumeTitle(): ?string
    {
        return $this->perfume_title;
    }

    public function setPerfumeTitle(string $perfume_title): static
    {
        $this->perfume_title = $perfume_title;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getOlfactiveFamilyId(): ?OlfactiveFamily
    {
        return $this->olfactive_family_id;
    }

    public function setOlfactiveFamilyId(?OlfactiveFamily $olfactive_family_id): static
    {
        $this->olfactive_family_id = $olfactive_family_id;

        return $this;
    }

    /**
     * @return Collection<int, Notes>
     */
    public function getNoteId(): Collection
    {
        return $this->note_id;
    }

    public function addNoteId(Notes $noteId): static
    {
        if (!$this->note_id->contains($noteId)) {
            $this->note_id->add($noteId);
        }

        return $this;
    }

    public function removeNoteId(Notes $noteId): static
    {
        $this->note_id->removeElement($noteId);

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMediasId(): Collection
    {
        return $this->medias_id;
    }

    public function addMediasId(Media $mediasId): static
    {
        if (!$this->medias_id->contains($mediasId)) {
            $this->medias_id->add($mediasId);
            $mediasId->setPerfume($this);
        }

        return $this;
    }

    public function removeMediasId(Media $mediasId): static
    {
        if ($this->medias_id->removeElement($mediasId)) {
            // set the owning side to null (unless already changed)
            if ($mediasId->getPerfume() === $this) {
                $mediasId->setPerfume(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Purchase>
     */
    public function getPurchase(): Collection
    {
        return $this->purchase;
    }

    public function addPurchase(Purchase $purchase): static
    {
        if (!$this->purchase->contains($purchase)) {
            $this->purchase->add($purchase);
            $purchase->setPerfume($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): static
    {
        if ($this->purchase->removeElement($purchase)) {
            // set the owning side to null (unless already changed)
            if ($purchase->getPerfume() === $this) {
                $purchase->setPerfume(null);
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
            $favorite->addPerfumeId($this);
        }

        return $this;
    }

    public function removeFavorite(Favorites $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            $favorite->removePerfumeId($this);
        }

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }
}
