<?php

namespace App\Entity;

use App\Repository\ConnexionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConnexionRepository::class)]
class Connexion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_connexion = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_utilisateur = null;

    #[ORM\Column]
    private ?\DateTime $dateConnexion = null;


    public function getId(): ?string
    {
        return $this->id_connexion;
    }

    public function setId(string $id_connexion): static
    {
        $this->id_connexion = $id_connexion;

        return $this;
    }

    public function getIdUtilisateur(): ?string
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(string $id_utilisateur): static
    {
        $this->id_utilisateur = $id_utilisateur;

        return $this;
    }

    public function getDateConnexion(): ?\DateTime
    {
        return $this->dateConnexion;
    }

    public function setDateConnexion(\DateTime $dateConnexion): static
    {
        $this->dateConnexion = $dateConnexion;

        return $this;
    }
}
