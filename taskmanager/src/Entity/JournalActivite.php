<?php

namespace App\Entity;

use App\Repository\JournalActiviteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JournalActiviteRepository::class)]
#[ORM\Table(name: 'journal_activite')]
class JournalActivite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_journal')]
    private ?int $id = null;

    #[ORM\Column(name: 'date_modification')]
    private \DateTimeImmutable $dateModification;

    #[ORM\Column(name: 'champ_modifie', length: 50)]
    private ?string $champModifie = null;

    #[ORM\Column(name: 'ancienne_valeur', type: Types::TEXT, nullable: true)]
    private ?string $ancienneValeur = null;

    #[ORM\Column(name: 'nouvelle_valeur', type: Types::TEXT, nullable: true)]
    private ?string $nouvelleValeur = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'journaux')]
    #[ORM\JoinColumn(name: 'id_tache', referencedColumnName: 'id_tache', nullable: false, onDelete: 'CASCADE')]
    private ?Tache $tache = null;

    public function __construct() { $this->dateModification = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getDateModification(): \DateTimeImmutable { return $this->dateModification; }
    public function getChampModifie(): ?string { return $this->champModifie; }
    public function setChampModifie(string $c): static { $this->champModifie = $c; return $this; }
    public function getAncienneValeur(): ?string { return $this->ancienneValeur; }
    public function setAncienneValeur(?string $v): static { $this->ancienneValeur = $v; return $this; }
    public function getNouvelleValeur(): ?string { return $this->nouvelleValeur; }
    public function setNouvelleValeur(?string $v): static { $this->nouvelleValeur = $v; return $this; }
    public function getUtilisateur(): ?Utilisateur { return $this->utilisateur; }
    public function setUtilisateur(?Utilisateur $u): static { $this->utilisateur = $u; return $this; }
    public function getTache(): ?Tache { return $this->tache; }
    public function setTache(?Tache $t): static { $this->tache = $t; return $this; }
}
