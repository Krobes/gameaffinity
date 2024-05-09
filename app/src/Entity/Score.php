<?php

namespace App\Entity;

use App\Repository\ScoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScoreRepository::class)]
class Score
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 0)]
    private ?string $score = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'scores')]
    private Collection $user;

    /**
     * @var Collection<int, Game>
     */
    #[ORM\ManyToMany(targetEntity: Game::class, inversedBy: 'scores')]
    private Collection $game;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->game = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getScore(): ?string
    {
        return $this->score;
    }

    public function setScore(string $score): static
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): static
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Game>
     */
    public function getGame(): Collection
    {
        return $this->game;
    }

    public function addGame(Game $game): static
    {
        if (!$this->game->contains($game)) {
            $this->game->add($game);
        }

        return $this;
    }

    public function removeGame(Game $game): static
    {
        $this->game->removeElement($game);

        return $this;
    }
}
