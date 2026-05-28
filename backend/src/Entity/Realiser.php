<?php

namespace App\Entity;

use App\Repository\RealiserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealiserRepository::class)]
class Realiser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_realisation = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_tache = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_campagne = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_ouvrier = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_intrant = null;

    #[ORM\Column]
    private ?\DateTime $dateRealisation = null;

    #[ORM\Column(nullable: true)]
    private ?float $quantiteIntrant = null;

    public function getId(): ?string
    {
        return $this->id_tache;
    }

    public function setId(string $id_tache): static
    {
        $this->id_tache = $id_tache;

        return $this;
    }

    public function getIdCampagne(): ?string
    {
        return $this->id_campagne;
    }

    public function setIdCampagne(string $id_campagne): static
    {
        $this->id_campagne = $id_campagne;

        return $this;
    }

    public function getIdOuvrier(): ?string
    {
        return $this->id_ouvrier;
    }

    public function setIdOuvrier(string $id_ouvrier): static
    {
        $this->id_ouvrier = $id_ouvrier;

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

    public function getIdRealisation(): ?string
    {
        return $this->id_realisation;
    }

    public function setIdRealisation(string $id_realisation): static
    {
        $this->id_realisation = $id_realisation;

        return $this;
    }

    public function getDateRealisation(): ?\DateTime
    {
        return $this->dateRealisation;
    }

    public function setDateRealisation(\DateTime $dateRealisation): static
    {
        $this->dateRealisation = $dateRealisation;

        return $this;
    }

    public function getQuantiteIntrant(): ?float
    {
        return $this->quantiteIntrant;
    }

    public function setQuantiteIntrant(?float $quantiteIntrant): static
    {
        $this->quantiteIntrant = $quantiteIntrant;

        return $this;
    }
}
