<?php

namespace App\Entity;

use App\Repository\RoleUtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoleUtilisateurRepository::class)]
#[ORM\Table(name: "RoleUtilisateur")]
class RoleUtilisateur
{
    #[ORM\Id]
    #[ORM\Column(name: "id_role", type: Types::BIGINT)]
    private ?string $id_role = null;

    #[ORM\Column(name: "nomRoleUser", length: 30)]
    private ?string $nomRoleUser = null;

    #[ORM\Column(name: "creationPlantation")]
    private bool $creationPlantation = false;

    #[ORM\Column(name: "creationParcelle")]
    private bool $creationParcelle = false;

    #[ORM\Column(name: "creationOuvrier")]
    private bool $creationOuvrier = false;

    #[ORM\Column(name: "saisieTache")]
    private bool $saisieTache= false;

    #[ORM\Column(name: "creationCampagne")]
    private bool $creationCampagne = false;

    public function getId(): ?string
    {
        return $this->id_role;
    }

    public function setId(string $id): static {
        $this->id_role = $id;
        return $this;
    }

    public function getNomRoleUser(): ?string
    {
        return $this->nomRoleUser;
    }

    public function setNomRoleUser(string $nomRoleUser): static
    {
        $this->nomRoleUser = $nomRoleUser;

        return $this;
    }

    public function getIdRole(): ?string
    {
        return $this->id_role;
    }

    public function setIdRole(?string $id_role): void
    {
        $this->id_role = $id_role;
    }

    public function isCreationPlantation(): bool
    {
        return $this->creationPlantation;
    }

    public function setCreationPlantation(bool $creationPlantation): void
    {
        $this->creationPlantation = $creationPlantation;
    }

    public function isCreationParcelle(): bool
    {
        return $this->creationParcelle;
    }

    public function setCreationParcelle(bool $creationParcelle): void
    {
        $this->creationParcelle = $creationParcelle;
    }

    public function isCreationOuvrier(): bool
    {
        return $this->creationOuvrier;
    }

    public function setCreationOuvrier(bool $creationOuvrier): void
    {
        $this->creationOuvrier = $creationOuvrier;
    }

    public function isSaisieTache(): bool
    {
        return $this->saisieTache;
    }

    public function setSaisieTache(bool $saisieTache): void
    {
        $this->saisieTache = $saisieTache;
    }

    public function isCreationCampagne(): bool
    {
        return $this->creationCampagne;
    }

    public function setCreationCampagne(bool $creationCampagne): void
    {
        $this->creationCampagne = $creationCampagne;
    }

    public function __toString(): string
    {
        return $this->nomRoleUser ?? 'Rôle sans nom';
    }

}
