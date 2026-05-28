<?php

namespace App\Entity;

use App\Repository\DepensesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepensesRepository::class)]
class Depenses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_depenses = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_plantation = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_intrant = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?\DateTime $dateMontant = null;

    #[ORM\Column(length: 50)]
    private ?string $description = null;

    public function getId(): ?string
    {
        return $this->id_depenses;
    }

    public function setId(string $id_depenses): static
    {
        $this->id_depenses = $id_depenses;

        return $this;
    }

    public function getIdPlantation(): ?string
    {
        return $this->id_plantation;
    }

    public function setIdPlantation(string $id_plantation): static
    {
        $this->id_plantation = $id_plantation;

        return $this;
    }

    public function getIdIntrant(): ?string
    {
        return $this->id_intrant;
    }

    public function setIdIntrant(string $id_intrant): static
    {
        $this->id_intrant = $id_intrant;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDateMontant(): ?\DateTime
    {
        return $this->dateMontant;
    }

    public function setDateMontant(\DateTime $dateMontant): static
    {
        $this->dateMontant = $dateMontant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
