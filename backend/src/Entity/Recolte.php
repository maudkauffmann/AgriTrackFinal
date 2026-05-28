<?php

namespace App\Entity;

use App\Repository\RecolteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecolteRepository::class)]
class Recolte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_recolte = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_unite = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_campagne = null;

    #[ORM\Column]
    private ?float $quantite = null;

    #[ORM\Column]
    private ?\DateTime $dateRecolte = null;

    #[ORM\Column]
    private ?\DateTime $dateVente = null;

    #[ORM\Column]
    private ?float $prixUnitaire = null;

    #[ORM\Column]
    private ?float $quantiteVendue = null;

    #[ORM\Column(length: 30)]
    private ?string $modePaiement = null;

    #[ORM\Column(length: 20)]
    private ?string $statutPaiement = null;

    public function getId(): ?string
    {
        return $this->id_recolte;
    }

    public function setId(string $id_recolte): static
    {
        $this->id_recolte = $id_recolte;

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

    public function getIdCampagne(): ?string
    {
        return $this->id_campagne;
    }

    public function setIdCampagne(string $id_campagne): static
    {
        $this->id_campagne = $id_campagne;

        return $this;
    }

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getDateRecolte(): ?\DateTime
    {
        return $this->dateRecolte;
    }

    public function setDateRecolte(\DateTime $dateRecolte): static
    {
        $this->dateRecolte = $dateRecolte;

        return $this;
    }

    public function getDateVente(): ?\DateTime
    {
        return $this->dateVente;
    }

    public function setDateVente(\DateTime $dateVente): static
    {
        $this->dateVente = $dateVente;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    public function getQuantiteVendue(): ?float
    {
        return $this->quantiteVendue;
    }

    public function setQuantiteVendue(float $quantiteVendue): static
    {
        $this->quantiteVendue = $quantiteVendue;

        return $this;
    }

    public function getModePaiement(): ?string
    {
        return $this->modePaiement;
    }

    public function setModePaiement(string $modePaiement): static
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    public function getStatutPaiement(): ?string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): static
    {
        $this->statutPaiement = $statutPaiement;

        return $this;
    }
}
