<?php

namespace App\Entity;

use App\Repository\TypeCultureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeCultureRepository::class)]
class TypeCulture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_tp_culture", type: Types::BIGINT)]
    private ?string $id_tp_culture = null;

    #[ORM\Column(name: "nomTpCulture", length: 30)]
    private ?string $nomTpCulture = null;

    public function getId(): ?string
    {
        return $this->id_tp_culture;
    }

    public function setId(string $id_tp_culture): static
    {
        $this->id_tp_culture = $id_tp_culture;

        return $this;
    }

    public function getNomTpCulture(): ?string
    {
        return $this->nomTpCulture;
    }

    public function setNomTpCulture(string $nomTpCulture): static
    {
        $this->nomTpCulture = $nomTpCulture;

        return $this;
    }

    public function __toString(): string
    {
        return $this->nomTpCulture ?? 'Type inconnu';
    }
}
