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

    #[ORM\ManyToOne(targetEntity: TypeIntrant::class)]
    #[ORM\JoinColumn(name: "id_tp_intrant", referencedColumnName: "id_tp_intrant", nullable: false)]
    private ?TypeIntrant $typeIntrant = null;

    #[ORM\ManyToOne(targetEntity: Unite::class)]
    #[ORM\JoinColumn(name: "id_unite", referencedColumnName: "id_unite", nullable: false)]
    private ?Unite $unite = null;

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

    public function getTypeIntrant(): ?TypeIntrant
    {
        return $this->typeIntrant;
    }

    public function setTypeIntrant(?TypeIntrant $typeIntrant): static
    {
        $this->typeIntrant = $typeIntrant;
        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(?Unite $id_unite): static
    {
        $this->unite = $id_unite;
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
