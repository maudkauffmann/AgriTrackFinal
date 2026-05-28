<?php

namespace App\Entity;

use App\Repository\PlantationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
#[ORM\Entity(repositoryClass: PlantationRepository::class)]
class Plantation
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_plantation", type: Types::BIGINT)]
    #[Groups(['plantation:read'])]
    private ?string $id_plantation = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id_utilisateur", nullable: false)]
    private ?Utilisateur $id_utilisateur = null;

    #[ORM\Column(name: "nomPlantation", length: 30)]
    #[Groups(['plantation:read'])]
    private ?string $nomPlantation = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['plantation:read'])]
    private ?float $longitude = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Groups(['plantation:read'])]
    private ?float $latitude= null;

    #[ORM\Column(length: 50)]
    #[Groups(['plantation:read'])]
    private ?string $ville= null;

    #[ORM\Column(length: 300)]
    #[Groups(['plantation:read'])]
    private ?string $indications= null;

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): void
    {
        $this->ville = $ville;
    }

    public function getIndications(): ?string
    {
        return $this->indications;
    }

    public function setIndications(?string $indications): void
    {
        $this->indications = $indications;
    }

    #[Groups(['plantation:read'])]
    public function getId(): ?string
    {
        return $this->id_plantation;
    }

    public function getIdPlantation(): ?string
    {
        return $this->id_plantation;
    }

    public function setId(string $id_plantation): static
    {
        $this->id_plantation = $id_plantation;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $user): static
    {
        $this->id_utilisateur = $user;
        return $this;
    }
    public function getNomPlantation(): ?string
    {
        return $this->nomPlantation;
    }

    public function setNomPlantation(string $nomPlantation): static
    {
        $this->nomPlantation = $nomPlantation;

        return $this;
    }

    public function __toString(): string {
        return $this->nomPlantation ?? 'Plantation';
    }
}
