<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TeamsRepository::class)
 */
class Team
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
    private $wins = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $losses = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $ties = 0;

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
}
