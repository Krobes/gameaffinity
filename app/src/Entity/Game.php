<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $date_release = null;

    #[ORM\Column(length: 255)]
    private ?string $cover = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, Score>
     */
    #[ORM\ManyToMany(targetEntity: Score::class, mappedBy: 'game')]
    private Collection $scores;

    #[ORM\ManyToOne(inversedBy: 'games')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Developer $developer = null;

    /**
     * @var Collection<int, Genre>
     */
    #[ORM\ManyToMany(targetEntity: Genre::class, inversedBy: 'games')]
    private Collection $genres;

    /**
     * @var Collection<int, Platform>
     */
    #[ORM\ManyToMany(targetEntity: Platform::class, inversedBy: 'games')]
    private Collection $platforms;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $summary = null;

    public function __construct()
    {
        $this->scores = new ArrayCollection();
        $this->genres = new ArrayCollection();
        $this->platforms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDateRelease(): ?string
    {
        return $this->date_release;
    }

    public function setDateRelease(?string $date_release): static
    {
        $this->date_release = $date_release;

        return $this;
    }

    public function getCover(): ?string
    {
        return $this->cover;
    }

    public function setCover(string $cover): static
    {
        $this->cover = $cover;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Score>
     */
    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function addScore(Score $score): static
    {
        if (!$this->scores->contains($score)) {
            $this->scores->add($score);
            $score->addGame($this);
        }

        return $this;
    }

    public function removeScore(Score $score): static
    {
        if ($this->scores->removeElement($score)) {
            $score->removeGame($this);
        }

        return $this;
    }

    public function getDeveloper(): ?Developer
    {
        return $this->developer;
    }

    public function setDeveloper(?Developer $developer): static
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * @return Collection<int, Genre>
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    public function addGenre(Genre $genre): static
    {
        if (!$this->genres->contains($genre)) {
            $this->genres->add($genre);
        }

        return $this;
    }

    public function removeGenre(Genre $genre): static
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @return Collection<int, Platform>
     */
    public function getPlatforms(): Collection
    {
        return $this->platforms;
    }

    public function addPlatform(Platform $platform): static
    {
        if (!$this->platforms->contains($platform)) {
            $this->platforms->add($platform);
        }

        return $this;
    }

    public function removePlatform(Platform $platform): static
    {
        $this->platforms->removeElement($platform);

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;

        return $this;
    }
}
