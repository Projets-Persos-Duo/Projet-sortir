<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SortieRepository::class)
 */
class Sortie
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
    private $nom;

    /**
     * @ORM\Column(type="date")
     */
    private $date_annonce;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_annonce;

    /**
     * @ORM\Column(type="date")
     */
    private $date_cloture;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_cloture;

    /**
     * @ORM\Column(type="date")
     */
    private $date_debut;

    /**
     * @ORM\Column(type="time")
     */
    private $heure_debut;

    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $heure_fin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $raison_annulation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $limite_participants;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $infos_sortie;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $est_cloture;

    /**
     * @ORM\ManyToOne(targetEntity=Thematiques::class, inversedBy="sorties")
     * @ORM\JoinColumn(nullable=false)
     */
    private $theme;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="sortie")
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="sortie")
     */
    private $groupes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sortiesOrganisees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $organisateur;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="sortiesParticipees")
     */
    private $participants;

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDateAnnonce(): ?\DateTimeInterface
    {
        return $this->date_annonce;
    }

    public function setDateAnnonce(\DateTimeInterface $date_annonce): self
    {
        $this->date_annonce = $date_annonce;

        return $this;
    }

    public function getHeureAnnonce(): ?\DateTimeInterface
    {
        return $this->heure_annonce;
    }

    public function setHeureAnnonce(\DateTimeInterface $heure_annonce): self
    {
        $this->heure_annonce = $heure_annonce;

        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->date_cloture;
    }

    public function setDateCloture(\DateTimeInterface $date_cloture): self
    {
        $this->date_cloture = $date_cloture;

        return $this;
    }

    public function getHeureCloture(): ?\DateTimeInterface
    {
        return $this->heure_cloture;
    }

    public function setHeureCloture(\DateTimeInterface $heure_cloture): self
    {
        $this->heure_cloture = $heure_cloture;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heure_debut;
    }

    public function setHeureDebut(\DateTimeInterface $heure_debut): self
    {
        $this->heure_debut = $heure_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heure_fin;
    }

    public function setHeureFin(?\DateTimeInterface $heure_fin): self
    {
        $this->heure_fin = $heure_fin;

        return $this;
    }

    public function getRaisonAnnulation(): ?string
    {
        return $this->raison_annulation;
    }

    public function setRaisonAnnulation(?string $raison_annulation): self
    {
        $this->raison_annulation = $raison_annulation;

        return $this;
    }

    public function getLimiteParticipants(): ?int
    {
        return $this->limite_participants;
    }

    public function setLimiteParticipants(?int $limite_participants): self
    {
        $this->limite_participants = $limite_participants;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infos_sortie;
    }

    public function setInfosSortie(?string $infos_sortie): self
    {
        $this->infos_sortie = $infos_sortie;

        return $this;
    }

    public function getEstCloture(): ?bool
    {
        return $this->est_cloture;
    }

    public function setEstCloture(?bool $est_cloture): self
    {
        $this->est_cloture = $est_cloture;

        return $this;
    }

    public function getGroupes(): ?Groupe
    {
        return $this->groupes;
    }

    public function setGroupes(?Groupe $groupes): self
    {
        $this->groupes = $groupes;

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getTheme(): ?Thematiques
    {
        return $this->theme;
    }

    public function setTheme(?Thematiques $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(User $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
        }

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function removeParticipant(User $participant): self
    {
        $this->participants->removeElement($participant);

        return $this;
    }
}
