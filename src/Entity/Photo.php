<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhotoRepository::class)
 */
class Photo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Chemin sur disque dur
     * @ORM\Column(type="string", length=255)
     */
    private ?string $chemin_dd;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isProfilePicture;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\ManyToMany(targetEntity=Sortie::class, mappedBy="photos")
     */
    private $sorties;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChemindd(): ?string
    {
        return $this->chemin_dd;
    }

    public function setChemindd(string $chemin_dd): self
    {
        $this->chemin_dd = $chemin_dd;

        return $this;
    }

    public function getIsProfilePicture(): ?bool
    {
        return $this->isProfilePicture;
    }

    public function setIsProfilePicture(bool $isProfilePicture): self
    {
        $this->isProfilePicture = $isProfilePicture;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties[] = $sorty;
            $sorty->addPhoto($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            $sorty->removePhoto($this);
        }

        return $this;
    }
}
