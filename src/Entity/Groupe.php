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
    private $sortie;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="groupesGeres", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    public function __construct()
    {
        $this->membres = new ArrayCollection();
        $this->sortie = new ArrayCollection();
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
    public function getSortie(): Collection
    {
        return $this->sortie;
    }

    public function addSortie(Sortie $sortie): self
    {
        if (!$this->sortie->contains($sortie)) {
            $this->sortie[] = $sortie;
            $sortie->setGroupes($this);
        }

        return $this;
    }

    public function removeSortie(Sortie $sortie): self
    {
        if ($this->sortie->removeElement($sortie)) {
            // set the owning side to null (unless already changed)
            if ($sortie->getGroupe() === $this) {
                $sortie->setGroupes(null);
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
