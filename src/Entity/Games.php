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
    private $away_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $home_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $start;

    /**
     * @ORM\Column(type="integer")
     */
    private $winner_id;

    /**
     * @ORM\Column(type="integer")
     */
    private $loser_id;

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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAwayId(): ?int
    {
        return $this->away_id;
    }

    public function setAwayId(int $away_id): self
    {
        $this->away_id = $away_id;

        return $this;
    }

    public function getHomeId(): ?int
    {
        return $this->home_id;
    }

    public function setHomeId(int $home_id): self
    {
        $this->home_id = $home_id;

        return $this;
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

    public function getWinnerId(): ?int
    {
        return $this->winner_id;
    }

    public function setWinnerId(int $winner_id): self
    {
        $this->winner_id = $winner_id;

        return $this;
    }

    public function getLoserId(): ?int
    {
        return $this->loser_id;
    }

    public function setLoserId(int $loser_id): self
    {
        $this->loser_id = $loser_id;

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
}
