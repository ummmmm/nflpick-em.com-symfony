<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
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
    private $home_score = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $away_score = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $tied = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $away;

    /**
     * @ORM\ManyToOne(targetEntity=Team::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $home;

    /**
     * @ORM\ManyToOne(targetEntity=Week::class)
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

    public function getAway(): ?Team
    {
        return $this->away;
    }

    public function setAway(?Team $away): self
    {
        $this->away = $away;

        return $this;
    }

    public function getHome(): ?Team
    {
        return $this->home;
    }

    public function setHome(?Team $home): self
    {
        $this->home = $home;

        return $this;
    }

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): self
    {
        $this->week = $week;

        return $this;
    }

    // Helper Functions

	public function isFinal(): bool
	{
		return $this->getTied() == 1 || $this->getHomeScore() != $this->getAwayScore();
	}
}
