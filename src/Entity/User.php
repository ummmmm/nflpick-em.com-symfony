<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\Column(type="integer")
     */
    private $wins = 0;

    /**
     * @ORM\Column(type="integer")
     */
    private $losses = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid = false;

    /**
     * @ORM\Column(type="integer")
     */
    private $current_place = 1;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="integer")
     */
    private $last_on_dt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
	{
		return $this->getFirstname() . ' ' . $this->getLastname();
	}

    public function getFirstname(): ?string
    {
        return $this->first_name;
    }

    public function setFirstname(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->last_name;
    }

    public function setLastname(string $last_name): self
    {
        $this->last_name = $last_name;

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

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getCurrentPlace(): ?int
    {
        return $this->current_place;
    }

    public function setCurrentPlace(int $current_place): self
    {
        $this->current_place = $current_place;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    // Helper Functions

	public function getOrdinal(): ?string
	{
		$number	= $this->getCurrentPlace();
		$mod10	= $number % 10;
		$mod100 = $number % 100;

		if ( $mod100 >= 11 && $mod100 <= 13 )
		{
			$s = "th";
		}
		else if ( $mod10 === 1 )
		{
			$s = "st";
		}
		else if ( $mod10 === 2 )
		{
			$s = "nd";
		}
		else if ( $mod10 === 3 )
		{
			$s = "rd";
		}
		else
		{
			$s = "th";
		}

		return $s;
	}

    public function getLastOnDt(): ?int
    {
        return $this->last_on_dt;
    }

    public function setLastOnDt(int $last_on_dt): self
    {
        $this->last_on_dt = $last_on_dt;

        return $this;
    }
}
