<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
	private $encoder;

	public function __construct( UserPasswordEncoderInterface $encoder )
	{
		$this->encoder = $encoder;
	}

    public function load(ObjectManager $manager)
    {
    	$user = new User();
    	$user->setFirstname( 'Bob' );
    	$user->setLastname( 'Barker' );
    	$user->setEmail( 'bbarker@example.com' );
    	$user->setPassword( '$2y$13$M9jIMvn42iZndTx0TUsHje9oEnBKAQUcXG6zgFDjEsiRt9vwzg/UC' );
    	$user->setWins( 0 );
    	$user->setLosses( 0 );
    	$user->setPaid( true );
    	$user->setCurrentPlace( 1 );
    	$user->setActive( true );
    	$user->setMessage( '' );
    	$user->setRoles( [ 'ROLE_ADMIN' ] );

    	$manager->persist( $user );
        $manager->flush();
    }
}
