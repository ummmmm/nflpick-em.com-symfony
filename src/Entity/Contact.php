<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
	/**
	 * @Assert\NotBlank( message="Please enter your name" )
	 */
	private $name;

	/**
	 * @Assert\Email( message="Please enter a valid email address" )
	 */
	private $email;

	private $subject;

	/**
	 * @Assert\NotBlank( message="Please enter a message" )
	 */
	private $message;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName( string $name ): void
	{
		$this->name = $name;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail( string $email ): void
	{
		$this->email = $email;
	}

	public function getSubject(): ?string
	{
		return $this->subject;
	}

	public function setSubject( ?string $subject ): void
	{
		$this->subject = $subject;
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function setMessage( string $message ): void
	{
		$this->message = $message;
	}
}
