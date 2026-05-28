<?php

namespace App\Entity;

use App\Repository\CultureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CultureRepository::class)]
class Culture
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_culture", type: Types::BIGINT)]
    private ?string $id_culture = null;

    #[ORM\ManyToOne(targetEntity: TypeCulture::class)]
    #[ORM\JoinColumn(name: "id_tp_culture", referencedColumnName: "id_tp_culture", nullable: false)]
    private ?TypeCulture $id_tp_culture = null;

    #[ORM\Column(name: "nomCulture", length: 30)]
    private ?string $nomCulture = null;

    public function getId(): ?string
    {
        return $this->id_culture;
    }

    public function setId(string $id_culture): static
    {
        $this->id_culture = $id_culture;

        return $this;
    }

    public function getIdTpCulture(): ?TypeCulture
    {
        return $this->id_tp_culture;
    }

    public function setIdTpCulture(?TypeCulture $id_tp_culture): static
    {
        $this->id_tp_culture = $id_tp_culture;

        return $this;
    }

    public function getIdCulture(): ?string
    {
        return $this->id_culture;
    }

    public function getNomCulture(): ?string
    {
        return $this->nomCulture;
    }

    public function setNomCulture(string $nomCulture): static
    {
        $this->nomCulture = $nomCulture;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomCulture ?? 'Nouvelle Culture';
    }
}
