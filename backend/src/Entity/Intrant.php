<?php

namespace App\Entity;

use App\Repository\IntrantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntrantRepository::class)]
class Intrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_intrant", type: Types::BIGINT)]
    private ?string $id_intrant = null;

    #[ORM\Column(name: "id_tp_intrant", type: Types::BIGINT)]
    private ?string $id_tp_intrant = null;

    #[ORM\Column(name: "id_unite", type: Types::BIGINT)]
    private ?string $id_unite = null;

    #[ORM\Column(name: "nomIntrant", type: Types::STRING, length: 30)]
    private ?string $nomIntrant = null;

    #[ORM\Column(name: "stock", type: Types::BIGINT)]
    private ?string $stock = null;

    #[ORM\Column(name: "coutUnitaire", type: "float")]
    private ?float $coutUnitaire = null;

    public function getId(): ?string
    {
        return $this->id_intrant;
    }

    public function getIdIntrant(): ?string
    {
        return $this->id_intrant;
    }

    public function setIdIntrant(?string $id_intrant): static
    {
        $this->id_intrant = $id_intrant;
        return $this;
    }

    public function getIdTpIntrant(): ?string
    {
        return $this->id_tp_intrant;
    }

    public function setIdTpIntrant(?string $id_tp_intrant): static
    {
        $this->id_tp_intrant = $id_tp_intrant;
        return $this;
    }

    public function getIdUnite(): ?string
    {
        return $this->id_unite;
    }

    public function setIdUnite(?string $id_unite): static
    {
        $this->id_unite = $id_unite;
        return $this;
    }

    public function getNomIntrant(): ?string
    {
        return $this->nomIntrant;
    }

    public function setNomIntrant(?string $nomIntrant): static
    {
        $this->nomIntrant = $nomIntrant;
        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(?string $stock): static
    {
        $this->stock = $stock;
        return $this;
    }

    public function getCoutUnitaire(): ?float
    {
        return $this->coutUnitaire;
    }

    public function setCoutUnitaire(?float $coutUnitaire): static
    {
        $this->coutUnitaire = $coutUnitaire;
        return $this;
    }
}
