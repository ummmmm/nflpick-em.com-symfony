<?php

namespace App\DataFixtures;

use App\Entity\Week;
use App\Factory\GameFactory;
use App\Factory\NewsFactory;
use App\Factory\UserFactory;
use App\Factory\WeekFactory;
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
    	$admin_user = UserFactory::createOne( [ 'first_name' => 'Bob', 'last_name' => 'Barker', 'email' => 'bbarker@example.com', 'roles' => [ 'ROLE_ADMIN' ], 'active' => true ] );
    	UserFactory::createMany( 5 );

    	NewsFactory::createMany( 5, [ 'user' => $admin_user ] );

		/*
		 * Teams
		 */
		$this->team_repository->insertAll();
		$teams = $this->team_repository->findAll();

    	/*
    	 * Weeks / Games
    	 */
		for ( $i = 0; $i < 17; $i++ )
		{
			$week = WeekFactory::new()
						->week( $i )
						->create();

			$teams_copy = $teams;
			shuffle( $teams_copy );

			while ( count( $teams_copy ) > 0 )
			{
				GameFactory::new()
					->week( $week->object() )
					->awayTeam( array_pop( $teams_copy ) )
					->homeTeam( array_pop( $teams_copy ) )
					->create();
			}
		}

    	$manager->flush();
    }
}
