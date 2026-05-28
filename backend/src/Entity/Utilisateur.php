<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_TEL_UTILISATEUR', fields: ['telUtilisateur'])]
#[ORM\Table(name: "Utilisateur")]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "id_utilisateur", type: Types::BIGINT)]
    private ?string $id_utilisateur = null;

    #[ORM\ManyToOne(targetEntity: RoleUtilisateur::class)]
    #[ORM\JoinColumn(name: "id_role", referencedColumnName: "id_role", nullable: false)]
    private ?RoleUtilisateur $id_role = null;

    #[ORM\Column(name: "nomUtilisateur", length: 30)]
    private ?string $nomUtilisateur = null;

    #[ORM\Column(name: "telUtilisateur")]
    private ?string $telUtilisateur = null;

    #[ORM\Column(length: 50)]
    private ?string $email = null;

    #[ORM\Column(name: "password", length: 255)]
    private ?string $password = null;

    /**
     * @var list<string> Les rôles pour la sécurité Symfony
     */
    private array $roles = [];


    public function getId(): ?string
    {
        return $this->id_utilisateur;
    }

    public function setId(?string $id): void
    {
        $this->id_utilisateur = $id;
    }

    public function getIdUtilisateur(): ?string
    {
        return $this->id_utilisateur;
    }

    public function setIdUtilisateur(?string $id): void
    {
        $this->id_utilisateur = $id;
    }

    public function getNomUtilisateur(): ?string
    {
        return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): static
    {
        $this->nomUtilisateur = $nomUtilisateur;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTelUtilisateur(): ?string
    {
        return $this->telUtilisateur;
    }

    public function setTelUtilisateur(string $telUtilisateur): static
    {
        $this->telUtilisateur = $telUtilisateur;
        return $this;
    }

    public function getIdRole(): ?RoleUtilisateur
    {
        return $this->id_role;
    }

    public function setIdRole(?RoleUtilisateur $id_role): static
    {
        $this->id_role = $id_role;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->telUtilisateur;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        if ($this->id_role) {
            $roles[] = $this->id_role->getNomRoleUser();
        }

        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function __serialize(): array
    {
        return [
            'id' => $this->id_utilisateur,
            'tel' => $this->telUtilisateur,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id_utilisateur = $data['id'] ?? null;
        $this->telUtilisateur = $data['tel'] ?? null;
    }
}
