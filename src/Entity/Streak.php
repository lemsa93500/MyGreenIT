<?php

namespace App\Entity;

use App\Repository\StreakRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StreakRepository::class)]
class Streak
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'streak', cascade: ['persist', 'remove'])]
    private ?User $user = null;

    #[ORM\Column]
    private ?int $currentCount = null;

    #[ORM\Column]
    private ?int $bestCount = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastDoneAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCurrentCount(): ?int
    {
        return $this->currentCount;
    }

    public function setCurrentCount(int $currentCount): static
    {
        $this->currentCount = $currentCount;

        return $this;
    }

    public function getBestCount(): ?int
    {
        return $this->bestCount;
    }

    public function setBestCount(int $bestCount): static
    {
        $this->bestCount = $bestCount;

        return $this;
    }

    public function getLastDoneAt(): ?\DateTimeImmutable
    {
        return $this->lastDoneAt;
    }

    public function setLastDoneAt(?\DateTimeImmutable $lastDoneAt): static
    {
        $this->lastDoneAt = $lastDoneAt;

        return $this;
    }
    public function __construct()
    {
        $this->currentCount = 0;
        $this->bestCount = 0;
        // $this->lastDoneAt = null; // première action pas encore faite
    }

}
