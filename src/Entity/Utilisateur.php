<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateur')]
#[UniqueEntity(fields: ['email'], message: 'Un compte existe déjà avec cet email.')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_utilisateur')]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(name: 'mot_de_passe')]
    private ?string $motDePasse = null;

    #[ORM\Column(length: 30)]
    private string $role = 'ROLE_USER';

    #[ORM\OneToMany(targetEntity: Projet::class, mappedBy: 'createur')]
    private Collection $projetsCrees;

    #[ORM\OneToMany(targetEntity: EstMembre::class, mappedBy: 'utilisateur', orphanRemoval: true)]
    private Collection $estMembres;

    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'affecteA')]
    private Collection $tachesAffectees;

    public function __construct()
    {
        $this->projetsCrees = new ArrayCollection();
        $this->estMembres = new ArrayCollection();
        $this->tachesAffectees = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getPrenom(): ?string { return $this->prenom; }
    public function setPrenom(string $prenom): static { $this->prenom = $prenom; return $this; }
    public function getNomComplet(): string { return $this->prenom . ' ' . $this->nom; }
    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }
    public function getMotDePasse(): ?string { return $this->motDePasse; }
    public function setMotDePasse(string $motDePasse): static { $this->motDePasse = $motDePasse; return $this; }
    public function getRole(): string { return $this->role; }
    public function setRole(string $role): static { $this->role = $role; return $this; }

    public function getUserIdentifier(): string { return (string) $this->email; }
    public function getPassword(): ?string { return $this->motDePasse; }
    public function getRoles(): array { return array_values(array_unique([$this->role, 'ROLE_USER'])); }
    public function eraseCredentials(): void {}

    public function getProjetsCrees(): Collection { return $this->projetsCrees; }
    public function getEstMembres(): Collection { return $this->estMembres; }
    public function getTachesAffectees(): Collection { return $this->tachesAffectees; }
    public function __toString(): string { return $this->getNomComplet(); }
}
