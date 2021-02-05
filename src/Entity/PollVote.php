<?php

namespace App\Entity;

use App\Repository\PollVoteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PollVoteRepository::class)
 */
class PollVote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=PollAnswer::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $answer;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="integer")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $ip;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?PollAnswer
    {
        return $this->answer;
    }

    public function setAnswer(?PollAnswer $answer): self
    {
        $this->answer = $answer;

        return $this;
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

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function setDate(int $date): self
    {
        $this->date = $date;

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
}
