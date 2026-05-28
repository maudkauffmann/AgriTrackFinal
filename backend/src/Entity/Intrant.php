<?php

namespace App\Entity;

use App\Repository\IntrantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: IntrantRepository::class)]
class Intrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_intrant = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_tp_intrant = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_unite = null;

    #[ORM\Column(length: 30)]
    private ?string $nomIntrant = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $stock = null;

    #[ORM\Column]
    private ?float $countUnitaire = null;

    public function getId(): ?string
    {
        return $this->id_intrant;
    }

    public function setId(string $id_intrant): static
    {
        $this->id_intrant = $id_intrant;

        return $this;
    }

    public function getIdTpIntrant(): ?string
    {
        return $this->id_tp_intrant;
    }

    public function setIdTpIntrant(string $id_tp_intrant): static
    {
        $this->id_tp_intrant = $id_tp_intrant;

        return $this;
    }

    public function getIdUnite(): ?string
    {
        return $this->id_unite;
    }

    public function setIdUnite(string $id_unite): static
    {
        $this->id_unite = $id_unite;

        return $this;
    }

    public function getNomIntrant(): ?string
    {
        return $this->nomIntrant;
    }

    public function setNomIntrant(string $nomIntrant): static
    {
        $this->nomIntrant = $nomIntrant;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(string $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getCountUnitaire(): ?float
    {
        return $this->countUnitaire;
    }

    public function setCountUnitaire(float $countUnitaire): static
    {
        $this->countUnitaire = $countUnitaire;

        return $this;
    }
}
