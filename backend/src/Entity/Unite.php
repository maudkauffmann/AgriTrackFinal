<?php

namespace App\Entity;

use App\Repository\UniteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_unite = null;

    #[ORM\Column(length: 30)]
    private ?string $nomUnite = null;


    public function getId(): ?string
    {
        return $this->id_unite;
    }

    public function setId(string $id_unite): static
    {
        $this->id_unite = $id_unite;

        return $this;
    }

    public function getNomUnite(): ?string
    {
        return $this->nomUnite;
    }

    public function setNomUnite(string $nomUnite): static
    {
        $this->nomUnite = $nomUnite;

        return $this;
    }
}
