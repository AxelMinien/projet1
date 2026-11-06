<?php

namespace App\Entity;

use App\Enum\Priorite;
use App\Enum\Statut;
use App\Repository\TacheRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
#[ORM\Table(name: 'tache')]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_tache')]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 20, enumType: Statut::class)]
    private Statut $statut = Statut::A_FAIRE;

    #[ORM\Column(length: 20, enumType: Priorite::class)]
    private Priorite $priorite = Priorite::MOYENNE;

    #[ORM\Column(name: 'date_echeance', type: Types::DATE_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $dateEcheance = null;

    #[ORM\Column(name: 'date_creation')]
    private \DateTimeImmutable $dateCreation;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(name: 'id_projet', referencedColumnName: 'id_projet', nullable: false, onDelete: 'CASCADE')]
    private ?Projet $projet = null;

    #[ORM\ManyToOne(inversedBy: 'tachesAffectees')]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: true, onDelete: 'SET NULL')]
    private ?Utilisateur $affecteA = null;

    #[ORM\OneToMany(targetEntity: JournalActivite::class, mappedBy: 'tache', orphanRemoval: true)]
    #[ORM\OrderBy(['dateModification' => 'DESC'])]
    private Collection $journaux;

    public function __construct()
    {
        $this->dateCreation = new \DateTimeImmutable();
        $this->journaux = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }
    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(?string $d): static { $this->description = $d; return $this; }
    public function getStatut(): Statut { return $this->statut; }
    public function setStatut(Statut $s): static { $this->statut = $s; return $this; }
    public function getPriorite(): Priorite { return $this->priorite; }
    public function setPriorite(Priorite $p): static { $this->priorite = $p; return $this; }
    public function getDateEcheance(): ?\DateTimeImmutable { return $this->dateEcheance; }
    public function setDateEcheance(?\DateTimeImmutable $d): static { $this->dateEcheance = $d; return $this; }
    public function getDateCreation(): \DateTimeImmutable { return $this->dateCreation; }
    public function getProjet(): ?Projet { return $this->projet; }
    public function setProjet(?Projet $p): static { $this->projet = $p; return $this; }
    public function getAffecteA(): ?Utilisateur { return $this->affecteA; }
    public function setAffecteA(?Utilisateur $u): static { $this->affecteA = $u; return $this; }
    public function getJournaux(): Collection { return $this->journaux; }

    public function isEnRetard(): bool
    {
        return $this->dateEcheance !== null
            && $this->statut !== Statut::TERMINEE
            && $this->dateEcheance < new \DateTimeImmutable('today');
    }
}
