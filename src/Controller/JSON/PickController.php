<?php


namespace App\Controller\JSON;


use App\Controller\JSONController;
use App\Entity\Game;
use App\Entity\Pick;
use App\Entity\User;
use App\Entity\Week;
use App\Repository\GameRepository;
use App\Repository\PickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PickController extends JSONController
{
	/**
	 * @Route( "/picks/game/{id}", name="app_picks_game", methods="POST" )
	 */
	public function makePick(Game $game, Request $request, EntityManagerInterface $entityManager, PickRepository $pickRepository )
	{
		/** @var User $user */
		$user		= $this->getUser();
		$winner_id	= $request->get( 'winner_id' );
		$loser_id	= $request->get( 'loser_id' );
		$home_team	= $game->getHome();
		$away_team	= $game->getAway();

		if ( ( $winner_id != $home_team->getId() && $winner_id != $away_team->getId() ) ||
			( $loser_id != $home_team->getId() && $loser_id != $away_team->getId() ) )
		{
			return $this->jsonFailure( '#Error#', 'Invalid game data' );
		}

		if ( time() > $game->getStart() )
		{
			return $this->jsonFailure( '#Error#', 'This game has already started and can no longer be updated' );
		}

		if ( ( $pick = $pickRepository->findOnePickByUserGame( $user, $game ) ) === null )
		{
			$pick = new Pick();
		}

		$pick->setGame( $game );
		$pick->setUser( $user );
		$pick->setIp( $request->getClientIp() );
		$pick->setWinner( $winner_id == $home_team->getId() ? $home_team : $away_team );
		$pick->setLoser( $loser_id == $home_team->getId() ? $home_team : $away_team );
		$pick->setWeek( $game->getWeek() );

		$entityManager->persist( $pick );
		$entityManager->flush();

		return $this->jsonSuccess();
	}

	/**
	 * @Route( "/picks/week/{id}/load", name="app_picks_week_load" )
	 */
	public function loadPicksByWeek( Week $week, GameRepository $gameRepository, PickRepository $pickRepository)
	{
		$user					= $this->getUser();
		$response				= array();
		$response[ 'week' ]		= $week;
		$response[ 'games' ]	= array();
		$games					= $gameRepository->findBy( [ 'week' => $week ], [ 'start' => 'ASC' ] );

		foreach ( $games as $game )
		{
			$pick		= $pickRepository->findOnePickByUserGame( $user, $game );
			$game_date	= new \DateTime();
			$game_date->setTimestamp( $game->getStart() );

			array_push( $response[ 'games' ], array( 'game' => $game, 'pick' => $pick, 'date_formatted' => $game_date->format( 'F d, Y' ), 'time_formatted' => $game_date->format( 'h:i a' ) ) );
		}

		return $this->jsonSuccess( $response );
	}
}
