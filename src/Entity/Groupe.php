<?php

namespace App\Entity;

use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 */
class Groupe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=user::class, inversedBy="groupes")
     */
    private $membres;



    /**
     * @ORM\OneToMany(targetEntity=Sortie::class, mappedBy="groupe")
     */
    private $sorties;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="groupesGeres", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $proprietaire;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|user[]
     */
    public function getMembres(): Collection
    {
        return $this->membres;
    }

    public function addMembre(user $user): self
    {
        if (!$this->membres->contains($user)) {
            $this->membres[] = $user;
        }

        return $this;
    }

    public function removeMembre(user $user): self
    {
        $this->membres->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Sortie[]
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sorties->contains($sortie)) {
            $this->sorties[] = $sortie;
            $sortie->setGroupe($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sorties->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getGroupe() === $this) {
                $sortie->setGroupe(null);
            }
        }

        return $this;
    }

    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }
}
