<?php

namespace App\DataFixtures;

use App\Factory\GameFactory;
use App\Factory\NewsFactory;
use App\Factory\PickFactory;
use App\Factory\PollAnswerFactory;
use App\Factory\PollFactory;
use App\Factory\PollVoteFactory;
use App\Factory\UserFactory;
use App\Factory\WeekFactory;
use App\Repository\TeamRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
	private $encoder;
	private $teamRepository;

	public function __construct( UserPasswordEncoderInterface $encoder, TeamRepository $teamRepository )
	{
		$this->encoder			= $encoder;
		$this->teamRepository	= $teamRepository;
	}

    public function load(ObjectManager $manager)
    {
    	/*
    	 * Users
    	 */
    	$admin_user = UserFactory::createOne( [ 'first_name' => 'Bob', 'last_name' => 'Barker', 'email' => 'bbarker@example.com', 'roles' => [ 'ROLE_ADMIN' ], 'active' => true ] );
    	UserFactory::createMany( 30 );

    	/*
    	 * News
    	 */
    	NewsFactory::createMany( 5, [ 'user' => $admin_user ] );

		/*
		 * Teams
		 */
		$this->teamRepository->insertAll();
		$teams = $this->teamRepository->findAll();

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

		/*
		 * Picks
		 */
		$users = UserFactory::randomSet( 5 );
		$games = GameFactory::randomSet( 50 );

		foreach ( $users as $user )
		{
			foreach ( $games as $game )
			{
				PickFactory::new()
					->user( $user->object() )
					->game( $game->object() )
					->create();
			}
		}

		/*
		 * Polls
		 */
		PollFactory::createMany( 3 );
		PollAnswerFactory::createMany( 5, [ 'poll' => PollFactory::random() ] );
		PollAnswerFactory::createMany( 5, [ 'poll' => PollFactory::random() ] );
		PollAnswerFactory::createMany( 5, [ 'poll' => PollFactory::random() ] );
		PollVoteFactory::createOne( [ 'answer' => PollAnswerFactory::random(), 'user'=> UserFactory::random() ] );
		PollVoteFactory::createOne( [ 'answer' => PollAnswerFactory::random(), 'user'=> UserFactory::random() ] );
		PollVoteFactory::createOne( [ 'answer' => PollAnswerFactory::random(), 'user'=> UserFactory::random() ] );

    	$manager->flush();
    }
}
