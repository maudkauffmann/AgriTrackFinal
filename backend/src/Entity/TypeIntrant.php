<?php

namespace App\Entity;

use App\Repository\TypeIntrantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeIntrantRepository::class)]
class TypeIntrant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::BIGINT)]
    private ?string $id_tp_intrant = null;

    #[ORM\Column(length: 30)]
    private ?string $nomTpIntrant = null;


    public function getId(): ?string
    {
        return $this->id_tp_intrant;
    }

    public function setId(string $id_tp_intrant): static
    {
        $this->id_tp_intrant = $id_tp_intrant;

        return $this;
    }

    public function getNomTpIntrant(): ?string
    {
        return $this->nomTpIntrant;
    }

    public function setNomTpIntrant(string $nomTpIntrant): static
    {
        $this->nomTpIntrant = $nomTpIntrant;

        return $this;
    }
}
