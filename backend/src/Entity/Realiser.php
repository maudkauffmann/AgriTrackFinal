<?php

namespace App\Entity;

use App\Repository\RealiserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RealiserRepository::class)]
#[ORM\Table(name: "realiser")]
class Realiser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_realisation", type: Types::BIGINT)]
    private ?string $id_realisation = null;

    #[ORM\ManyToOne(targetEntity: Tache::class, fetch: "EAGER")]
    #[ORM\JoinColumn(name: "id_tache", referencedColumnName: "id_tache", nullable: false)]
    private ?Tache $tache = null;

    #[ORM\ManyToOne(targetEntity: Campagne::class, fetch: "EAGER")]
    #[ORM\JoinColumn(name: "id_campagne", referencedColumnName: "id_campagne", nullable: false)]
    private ?Campagne $campagne = null;

    #[ORM\ManyToOne(targetEntity: Ouvrier::class, fetch: "EAGER")]
    #[ORM\JoinColumn(name: "id_ouvrier", referencedColumnName: "id_ouvrier", nullable: false)]
    private ?Ouvrier $ouvrier = null;

    #[ORM\ManyToOne(targetEntity: Intrant::class)]
    #[ORM\JoinColumn(name: "id_intrant", referencedColumnName: "id_intrant", nullable: true)]
    private ?Intrant $intrant = null;

    #[ORM\Column(name: "dateRealisation", type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateRealisation = null;

    #[ORM\Column(name: "quantiteIntrant", type: "float", nullable: true)]
    private ?float $quantiteIntrant = null;

    #[ORM\Column(name: "uuid_local", type: Types::STRING, length: 36, nullable: true)]
    private ?string $uuidLocal = null;

    public function getIdRealisation(): ?string { return $this->id_realisation; }

    public function getTache(): ?Tache { return $this->tache; }
    public function setTache(?Tache $tache): static { $this->tache = $tache; return $this; }

    public function getCampagne(): ?Campagne { return $this->campagne; }
    public function setCampagne(?Campagne $campagne): static { $this->campagne = $campagne; return $this; }

    public function getOuvrier(): ?Ouvrier { return $this->ouvrier; }
    public function setOuvrier(?Ouvrier $ouvrier): static { $this->ouvrier = $ouvrier; return $this; }

    public function getIntrant(): ?Intrant { return $this->intrant; }
    public function setIntrant(?Intrant $intrant): static { $this->intrant = $intrant; return $this; }

    public function getDateRealisation(): ?\DateTimeInterface { return $this->dateRealisation; }
    public function setDateRealisation(\DateTimeInterface $dateRealisation): static { $this->dateRealisation = $dateRealisation; return $this; }

    public function getQuantiteIntrant(): ?float { return $this->quantiteIntrant; }
    public function setQuantiteIntrant(?float $quantiteIntrant): static { $this->quantiteIntrant = $quantiteIntrant; return $this; }

    public function getUuidLocal(): ?string { return $this->uuidLocal; }
    public function setUuidLocal(?string $uuidLocal): static { $this->uuidLocal = $uuidLocal; return $this; }
}
