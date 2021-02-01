<?php


namespace App\Controller\Admin;


use App\Entity\Game;
use App\Entity\Week;
use App\Repository\GameRepository;
use App\Repository\WeekRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
	/**
	 * @Route( "/admin/games", name="app_admin_games" )
	 */
	public function index(WeekRepository $weekRepository, GameRepository $gameRepository )
	{
		$weeks		= $weekRepository->findAllOrderByWeek();
		$entries	= array();

		foreach ( $weeks as $week )
		{
			$games				= $gameRepository->findBy( ['week' => $week ], [ 'start' => 'ASC' ] );

			$entry				= array();
			$entry[ 'week' ]	= $week;
			$entry[ 'games' ]	= $games;
			$entry[ 'finals' ]	= 0;

			foreach ( $games as $game )
			{
				if ( $game->isFinal() )
				{
					$entry[ 'finals' ]++;
				}
			}

			array_push( $entries, $entry );
		}

		return $this->render( 'admin/games/list.html.twig', [ 'entries' => $entries ] );
	}

	/**
	 * @Route( "/admin/games/week/{id}", name="app_admin_games_week" )
	 */
	public function editGames( Week $week, GameRepository $gameRepository )
	{
		$games = $gameRepository->findAllByWeekOrderByStart( $week );

		return $this->render( 'admin/games/list_week.html.twig', [ 'week' => $week, 'games' => $games ] );
	}

	/**
	 * @Route( "/admin/games/{id}/edit", name="app_admin_game_edit" )
	 */
	public function editGame( Game $game )
	{

	}
}
