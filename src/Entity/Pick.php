<?php

namespace App\Entity;

use App\Repository\PickRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PickRepository::class)
 */
class Pick
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Games::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity=Teams::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $loser;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ip;

    /**
     * @ORM\ManyToOne(targetEntity=Weeks::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $week;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getGame(): ?Games
    {
        return $this->game;
    }

    public function setGame(?Games $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getWinner(): ?Teams
    {
        return $this->winner;
    }

    public function setWinner(?Teams $winner): self
    {
        $this->winner = $winner;

        return $this;
    }

    public function getLoser(): ?Teams
    {
        return $this->loser;
    }

    public function setLoser(?Teams $loser): self
    {
        $this->loser = $loser;

        return $this;
    }

    public function getIp(): ?string
    {
        return $this->ip;
    }

    public function setIp(string $ip): self
    {
        $this->ip = $ip;

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
