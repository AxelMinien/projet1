<?php

namespace App\Entity;

use App\Enum\TypeNotification;
use App\Repository\NotificationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
#[ORM\Table(name: 'notification')]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_notification')]
    private ?int $id = null;

    #[ORM\Column(length: 30, enumType: TypeNotification::class)]
    private TypeNotification $type = TypeNotification::TACHE_MODIFIEE;

    #[ORM\Column(length: 255)]
    private ?string $message = null;

    #[ORM\Column(name: 'date_envoi')]
    private \DateTimeImmutable $dateEnvoi;

    #[ORM\Column(name: 'est_lue')]
    private bool $estLue = false;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_utilisateur', referencedColumnName: 'id_utilisateur', nullable: false, onDelete: 'CASCADE')]
    private ?Utilisateur $destinataire = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(name: 'id_tache', referencedColumnName: 'id_tache', nullable: false, onDelete: 'CASCADE')]
    private ?Tache $tache = null;

    public function __construct() { $this->dateEnvoi = new \DateTimeImmutable(); }

    public function getId(): ?int { return $this->id; }
    public function getType(): TypeNotification { return $this->type; }
    public function setType(TypeNotification $t): static { $this->type = $t; return $this; }
    public function getMessage(): ?string { return $this->message; }
    public function setMessage(string $m): static { $this->message = $m; return $this; }
    public function getDateEnvoi(): \DateTimeImmutable { return $this->dateEnvoi; }
    public function estLue(): bool { return $this->estLue; }
    public function setEstLue(bool $e): static { $this->estLue = $e; return $this; }
    public function getDestinataire(): ?Utilisateur { return $this->destinataire; }
    public function setDestinataire(?Utilisateur $u): static { $this->destinataire = $u; return $this; }
    public function getTache(): ?Tache { return $this->tache; }
    public function setTache(?Tache $t): static { $this->tache = $t; return $this; }
}
