<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_tache", type: Types::BIGINT)]
    private ?string $id_tache = null;

    #[ORM\Column(name: "nomTache", length: 30)]
    private ?string $nomTache = null;

    #[ORM\Column(name: "uuid_local", type: "string", length: 36, unique: true, nullable: true)]
    private ?string $uuidLocal = null;

    #[ORM\ManyToOne(targetEntity: Parcelle::class)]
    #[ORM\JoinColumn(name: "parcelle_id", referencedColumnName: "id_parcelle", onDelete: "SET NULL")]
    private ?Parcelle $parcelle = null;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id_utilisateur", onDelete: "SET NULL")]
    private ?Utilisateur $ouvrier = null;

    public function getId(): ?string
    {
        return $this->id_tache;
    }

    public function setId(string $id_tache): static
    {
        $this->id_tache = $id_tache;

        return $this;
    }

    public function getIdTache(): ?string
    {
        return $this->id_tache;
    }

    public function getNomTache(): ?string
    {
        return $this->nomTache;
    }

    public function setNomTache(string $nomTache): static
    {
        $this->nomTache = $nomTache;

        return $this;
    }

    public function getUuidLocal(): ?string
    {
        return $this->uuidLocal;
    }

    public function setUuidLocal(?string $uuidLocal): void
    {
        $this->uuidLocal = $uuidLocal;
    }

    public function getParcelle(): ?Parcelle
    {
        return $this->parcelle;
    }

    public function setParcelle(?Parcelle $parcelle): void
    {
        $this->parcelle = $parcelle;
    }

    public function getOuvrier(): ?Utilisateur
    {
        return $this->ouvrier;
    }

    public function setOuvrier(?Utilisateur $ouvrier): void
    {
        $this->ouvrier = $ouvrier;
    }

}
