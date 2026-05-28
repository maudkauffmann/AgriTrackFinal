<?php

namespace App\Entity;

use App\Repository\CampagneRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneRepository::class)]
class Campagne
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_campagne", type: Types::BIGINT)]
    private ?string $id_campagne = null;

    #[ORM\ManyToOne(targetEntity: Parcelle::class)]
    #[ORM\JoinColumn(name: "id_parcelle", referencedColumnName: "id_parcelle", nullable: false)]
    private ?Parcelle $id_parcelle = null;

    #[ORM\ManyToOne(targetEntity: Culture::class)]
    #[ORM\JoinColumn(name: "id_culture", referencedColumnName: "id_culture", nullable: false)]
    private ?Culture $id_culture = null;

    #[ORM\Column(name: "nomCampagne", length: 30)]
    private ?string $nomCampagne = null;

    #[ORM\Column(name: "dateDebut", type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateDeb = null;

    #[ORM\Column(name: "dateFin", type: Types::DATE_MUTABLE)]
    private ?\DateTime $dateFin = null;

    public function getId(): ?string
    {
        return $this->id_campagne;
    }

    public function getIdCampagne(): ?string
    {
        return $this->id_campagne;
    }

    public function getIdParcelle(): ?Parcelle
    {
        return $this->id_parcelle;
    }

    public function setIdParcelle(?Parcelle $parcelle): static
    {
        $this->id_parcelle = $parcelle;
        return $this;
    }

    public function getIdCulture(): ?Culture
    {
        return $this->id_culture;
    }

    public function setIdCulture(?Culture $culture): static
    {
        $this->id_culture = $culture;
        return $this;
    }

    public function getNomCampagne(): ?string
    {
        return $this->nomCampagne;
    }

    public function setNomCampagne(string $nomCampagne): static
    {
        $this->nomCampagne = $nomCampagne;

        return $this;
    }

    public function getDateDeb(): ?\DateTime
    {
        return $this->dateDeb;
    }

    public function setDateDeb(\DateTime $dateDeb): static
    {
        $this->dateDeb = $dateDeb;

        return $this;
    }

    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTime $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function __toString(): string
    {
        $nom = $this->id_culture ? $this->id_culture->getNomCulture() : 'Sans nom';
        $debut = $this->dateDeb ? $this->dateDeb->format('d/m/Y') : '?';
        $fin = ($this->dateFin === null) ? "En cours" : $this->dateFin->format('d/m/Y');

        return $nom . " (du " . $debut . " au " . $fin . ")";
    }
}
