<?php

namespace App\Entity;

use App\Repository\OuvrierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OuvrierRepository::class)]
class Ouvrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name:"id_ouvrier", type: Types::BIGINT)]
    private ?string $id_ouvrier = null;

    #[ORM\Column(name:"nomOuvrier", length: 30)]
    private ?string $nomOuvrier = null;

    #[ORM\Column(name:"telOuvrier", type: Types::BIGINT)]
    private ?string $telOuvrier = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_utilisateur", referencedColumnName: "id_utilisateur", nullable: true)]
    private ?Utilisateur $id_utilisateur = null;

    public function getId(): ?string
    {
        return $this->id_ouvrier;
    }

    public function setId(string $id_ouvrier): static
    {
        $this->id_ouvrier = $id_ouvrier;

        return $this;
    }

    public function getIdOuvrier(): ?string
    {
        return $this->id_ouvrier;
    }

    public function getNomOuvrier(): ?string
    {
        return $this->nomOuvrier;
    }

    public function setNomOuvrier(string $nomOuvrier): static
    {
        $this->nomOuvrier = $nomOuvrier;

        return $this;
    }

    public function getTelOuvrier(): ?string
    {
        return $this->telOuvrier;
    }

    public function setTelOuvrier(string $telOuvrier): static
    {
        $this->telOuvrier = $telOuvrier;

        return $this;
    }

    public function getIdUtilisateur(): ?Utilisateur
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?Utilisateur $id_utilisateur): static
    {
        $this->id_utilisateur = $id_utilisateur;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nomOuvrier ?? 'Ouvrier sans nom';
    }
}
