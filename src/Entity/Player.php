<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: true)]
    private ?string $nickname = null;

    #[ORM\Column]
    private ?int $highestScore = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column]
    private ?\DateTime $updatedAt = null;

    /**
     * @var Collection<int, ScoreEntry>
     */
    #[ORM\OneToMany(targetEntity: ScoreEntry::class, mappedBy: 'player')]
    private Collection $scoreEntries;

    public function __construct()
    {
        $this->scoreEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    public function setNickname(string $nickname): static
    {
        $this->nickname = $nickname;

        return $this;
    }

    public function getHighestScore(): ?int
    {
        return $this->highestScore;
    }

    public function setHighestScore(int $highestScore): static
    {
        $this->highestScore = $highestScore;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, ScoreEntry>
     */
    public function getScoreEntries(): Collection
    {
        return $this->scoreEntries;
    }

    public function addScoreEntry(ScoreEntry $scoreEntry): static
    {
        if (!$this->scoreEntries->contains($scoreEntry)) {
            $this->scoreEntries->add($scoreEntry);
            $scoreEntry->setPlayer($this);
        }

        return $this;
    }

    public function removeScoreEntry(ScoreEntry $scoreEntry): static
    {
        if ($this->scoreEntries->removeElement($scoreEntry)) {
            // set the owning side to null (unless already changed)
            if ($scoreEntry->getPlayer() === $this) {
                $scoreEntry->setPlayer(null);
            }
        }

        return $this;
    }
}
