<?php

namespace App\Entity;

use App\Repository\TeamsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamsRepository::class)
 */
class Teams
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $conference;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stadium;

    /**
     * @ORM\Column(type="integer")
     */
    private $wins;

    /**
     * @ORM\Column(type="integer")
     */
    private $losses;

    /**
     * @ORM\Column(type="integer")
     */
    private $ties;

    /**
     * @ORM\OneToMany(targetEntity=Games::class, mappedBy="away")
     */
    private $away_games;

    /**
     * @ORM\OneToMany(targetEntity=Games::class, mappedBy="home")
     */
    private $home_games;

    public function __construct()
    {
        $this->away_games = new ArrayCollection();
        $this->home_games = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getConference(): ?string
    {
        return $this->conference;
    }

    public function setConference(string $conference): self
    {
        $this->conference = $conference;

        return $this;
    }

    public function getStadium(): ?string
    {
        return $this->stadium;
    }

    public function setStadium(string $stadium): self
    {
        $this->stadium = $stadium;

        return $this;
    }

    public function getWins(): ?int
    {
        return $this->wins;
    }

    public function setWins(int $wins): self
    {
        $this->wins = $wins;

        return $this;
    }

    public function getLosses(): ?int
    {
        return $this->losses;
    }

    public function setLosses(int $losses): self
    {
        $this->losses = $losses;

        return $this;
    }

    public function getTies(): ?int
    {
        return $this->ties;
    }

    public function setTies(int $ties): self
    {
        $this->ties = $ties;

        return $this;
    }

    /**
     * @return Collection|Games[]
     */
    public function getAwayGames(): Collection
    {
        return $this->away_games;
    }

    public function addAwayGame(Games $awayGame): self
    {
        if (!$this->away_games->contains($awayGame)) {
            $this->away_games[] = $awayGame;
            $awayGame->setAway($this);
        }

        return $this;
    }

    public function removeAwayGame(Games $awayGame): self
    {
        if ($this->away_games->removeElement($awayGame)) {
            // set the owning side to null (unless already changed)
            if ($awayGame->getAway() === $this) {
                $awayGame->setAway(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Games[]
     */
    public function getHomeGames(): Collection
    {
        return $this->home_games;
    }

    public function addHomeGame(Games $homeGame): self
    {
        if (!$this->home_games->contains($homeGame)) {
            $this->home_games[] = $homeGame;
            $homeGame->setHome($this);
        }

        return $this;
    }

    public function removeHomeGame(Games $homeGame): self
    {
        if ($this->home_games->removeElement($homeGame)) {
            // set the owning side to null (unless already changed)
            if ($homeGame->getHome() === $this) {
                $homeGame->setHome(null);
            }
        }

        return $this;
    }
}
