<?php

namespace App\Entity;

use App\Repository\PhotoRepository;
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
}
