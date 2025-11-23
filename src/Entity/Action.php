<?php

namespace App\Entity;

use App\Repository\ActionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ActionRepository::class)]
class Action
{
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->userActions = new ArrayCollection();
        $this->isActive = true;
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank(message: "Le titre est obligatoire.")]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "La description est obligatoire.")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive(message: "Les points doivent être positifs.")]
    private ?int $basePoints = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, UserAction>
     */
    #[ORM\OneToMany(targetEntity: UserAction::class, mappedBy: 'action')]
    private Collection $userActions;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(
        choices: ['PC', 'Réseau', 'Mail', 'Cloud', 'Dev', 'Matériel', 'Bureau', 'Mobile'],
        message: 'Catégorie invalide.'
    )]
    private ?string $category = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'La difficulté doit être entre 1 et 5.')]
    private ?int $difficulty = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isActive = true;

    // ----------------- Getters & Setters -------------------

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getBasePoints(): ?int { return $this->basePoints; }
    public function setBasePoints(int $basePoints): static { $this->basePoints = $basePoints; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    /**
     * @return Collection<int, UserAction>
     */
    public function getUserActions(): Collection { return $this->userActions; }

    public function addUserAction(UserAction $userAction): static
    {
        if (!$this->userActions->contains($userAction)) {
            $this->userActions->add($userAction);
            $userAction->setAction($this);
        }
        return $this;
    }

    public function removeUserAction(UserAction $userAction): static
    {
        if ($this->userActions->removeElement($userAction)) {
            if ($userAction->getAction() === $this) {
                $userAction->setAction(null);
            }
        }
        return $this;
    }

    public function getCategory(): ?string { return $this->category; }
    public function setCategory(string $category): static { $this->category = $category; return $this; }

    public function getDifficulty(): ?int { return $this->difficulty; }
    public function setDifficulty(int $difficulty): static { $this->difficulty = $difficulty; return $this; }

    public function isActive(): ?bool { return $this->isActive; }
    public function setIsActive(bool $isActive): static { $this->isActive = $isActive; return $this; }
}
