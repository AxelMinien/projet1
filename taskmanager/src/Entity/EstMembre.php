<?php

namespace App\Entity;

use App\Repository\EstMembreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EstMembreRepository::class)]
#[ORM\Table(name: 'est_membre')]
class EstMembre
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'estMembres')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'estMembres')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id_projet', nullable: false, onDelete: 'CASCADE')]
    private ?Projet $projet = null;

    #[ORM\Column(name: 'date_ajout')]
    private \DateTimeImmutable $dateAjout;

    public function __construct() { $this->dateAjout = new \DateTimeImmutable(); }

    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $u): static { $this->utilisateur = $u; return $this; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(?Projet $p): static { $this->projet = $p; return $this; }
    public function getDateAjout(): \DateTimeImmutable { return $this->dateAjout; }
}
