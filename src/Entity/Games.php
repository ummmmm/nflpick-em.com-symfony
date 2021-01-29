<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GamesRepository::class)
 */
class Games
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $start;

    /**
     * @ORM\Column(type="integer")
     */
    private $home_score;

    /**
     * @ORM\Column(type="integer")
     */
    private $away_score;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tied;

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class, inversedBy="away_games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $away;

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class, inversedBy="home_games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $home;

    /**
     * @ORM\ManyToOne(targetEntity=Weeks::class, inversedBy="games")
     * @ORM\JoinColumn(nullable=false)
     */
    private $week;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?int
    {
        return $this->start;
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->home_score;
    }

    public function setHomeScore(int $home_score): self
    {
        $this->home_score = $home_score;

        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->away_score;
    }

    public function setAwayScore(int $away_score): self
    {
        $this->away_score = $away_score;

        return $this;
    }

    public function getTied(): ?bool
    {
        return $this->tied;
    }

    public function setTied(bool $tied): self
    {
        $this->tied = $tied;

        return $this;
    }

    public function getAway(): ?Teams
    {
        return $this->away;
    }

    public function setAway(?Teams $away): self
    {
        $this->away = $away;

        return $this;
    }

    public function getHome(): ?Teams
    {
        return $this->home;
    }

    public function setHome(?Teams $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getWeek(): ?Weeks
    {
        return $this->week;
    }

    public function setWeek(?Weeks $week): self
    {
        $this->week = $week;

        return $this;
    }
}
