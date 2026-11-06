<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
#[ORM\Table(name: 'projet')]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_projet')]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: 'date_creation')]
    private \DateTimeImmutable $dateCreation;

    #[ORM\ManyToOne(inversedBy: 'projetsCrees')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false)]
    private ?Utilisateur $createur = null;

    #[ORM\OneToMany(targetEntity: EstMembre::class, mappedBy: 'projet', cascade: ['persist'], orphanRemoval: true)]
    private Collection $estMembres;

    #[ORM\OneToMany(targetEntity: Tache::class, mappedBy: 'projet', orphanRemoval: true)]
    private Collection $taches;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->estMembres = new ArrayCollection();
        $this->taches = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getNom(): ?string { return $this->nom; }
    public function setNom(string $nom): static { $this->nom = $nom; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $description): static { $this->description = $description; return $this; }
    public function getDateCreation(): \DateTimeImmutable { return $this->dateCreation; }
    public function getCreateur(): ?Utilisateur { return $this->createur; }

    public function setCreateur(Utilisateur $createur): static
    {
        $this->createur = $createur;
        $this->ajouterMembre($createur);
        return $this;
    }

    public function getEstMembres(): Collection { return $this->estMembres; }

    public function ajouterMembre(Utilisateur $u): static
    {
        if (!$this->estMembre($u)) {
            $this->estMembres->add((new EstMembre())->setProjet($this)->setUtilisateur($u));
        }
        return $this;
    }

    public function retirerMembre(Utilisateur $u): static
    {
        foreach ($this->estMembres as $e) {
            if ($e->getUtilisateur() === $u) {
                $this->estMembres->removeElement($e);
            }
        }
        return $this;
    }

    public function estMembre(Utilisateur $u): bool
    {
        foreach ($this->estMembres as $e) {
            if ($e->getUtilisateur() === $u) {
                return true;
            }
        }
        return false;
    }

    public function getTaches(): Collection { return $this->taches; }
    public function __toString(): string { return (string) $this->nom; }
}
