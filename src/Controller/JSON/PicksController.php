<?php


namespace App\Controller\JSON;


use App\Controller\JSONController;
use App\Entity\Game;
use App\Entity\Pick;
use App\Entity\User;
use App\Repository\PickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PicksController extends JSONController
{
	/**
	 * @Route( "/picks/game/{id}", name="app_picks_game", methods="POST" )
	 */
	public function makePick(Game $game, Request $request, EntityManagerInterface $entity_manager, PickRepository $pick_repository )
	{
		/** @var User $user */
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
