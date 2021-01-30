<?php

namespace App\DataFixtures;

use App\Entity\Week;
use App\Factory\NewsFactory;
use App\Factory\UserFactory;
use App\Repository\TeamRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	private $encoder;
	private $team_repository;

	public function __construct( UserPasswordEncoderInterface $encoder, TeamRepository $team_repository )
	{
		$this->encoder			= $encoder;
		$this->team_repository	= $team_repository;
	}

    public function load(ObjectManager $manager)
    {
    	$admin_user = UserFactory::createOne( [ 'first_name' => 'Bob', 'last_name' => 'Barker', 'email' => 'bbarker@example.com', 'password' => 'P@ssw0rd', 'roles' => [ 'ROLE_ADMIN' ], 'active' => true ] );
    	UserFactory::createMany( 5 );

    	NewsFactory::createMany( 5, [ 'user' => $admin_user ] );

    	/*
    	 * Weeks
    	 */
		$first_sunday = strtotime( 'First Sunday of September' );

    	for ( $i = 0; $i < 17; $i++ )
		{
			$week = new Week();
			$week->setDate( $first_sunday + ( $i * 60 * 60 * 24 * 7 ) );
			$week->setLocked( false );

			$manager->persist( $week );
		}

    	/*
    	 * Teams
    	 */

		$this->team_repository->insertAll();

    	$manager->flush();
    }
}
