<?php


namespace App\Controller;


use App\Entity\Games;
use App\Entity\Pick;
use App\Entity\Weeks;
use App\Repository\GamesRepository;
use App\Repository\PickRepository;
use App\Repository\WeeksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PickController extends AbstractController
{
	/**
	 * @Route( "/picks", name="app_picks" )
	 */
	public function index(WeeksRepository $weeks_repository)
	{
		$weeks = $weeks_repository->findBy([], $orderBy = ['id' => 'ASC']);

		return $this->render('picks/index.html.twig', ['weeks' => $weeks]);
	}

	/**
	 * @Route( "/picks/week/{id}", name="app_picks_week" )
	 */
	public function weekList(Weeks $week, GamesRepository $game_repository)
	{
		$games = $game_repository->findBy(['week' => $week], ['start' => 'ASC']);

		return $this->render('picks/week_list.html.twig', ['week' => $week, 'games' => $games]);
	}
}

class JSONPickController extends JSONController
{
	/**
	 * @Route( "/picks/game/{id}", name="app_picks_game", methods="POST" )
	 */
	public function makePick( Games $game, Request $request, EntityManagerInterface $entity_manager, PickRepository $pick_repository )
	{
		/** @var \App\Entity\User $user */
		$user		= $this->getUser();
		$data		= json_decode( $request->getContent(), true );
		$winner_id	= $data[ 'winner_id' ];
		$loser_id	= $data[ 'loser_id' ];
		$home_team	= $game->getHome();
		$away_team	= $game->getAway();

		if ( ( $winner_id != $home_team->getId() && $winner_id != $away_team->getId() ) ||
			 ( $loser_id != $home_team->getId() && $loser_id != $away_team->getId() ) )
		{
			return $this->json_failure( '#Error#', 'Invalid game data' );
		}

		if ( time() > $game->getStart() )
		{
			return $this->json_failure( '#Error#', 'This game has already started and can no longer be updated' );
		}

		if ( ( $pick = $pick_repository->findOnePickByUserGame( $user, $game ) ) === null )
		{
			$pick = new Pick();
			$pick->setGame( $game );
			$pick->setUser( $user );
		}

		$pick->setIp( $request->getClientIp() );
		$pick->setWinner( $winner_id == $home_team->getId() ? $home_team : $away_team );
		$pick->setLoser( $loser_id == $home_team->getId() ? $home_team : $away_team );

		$entity_manager->persist( $pick );
		$entity_manager->flush();

		return $this->json_success();
	}
}