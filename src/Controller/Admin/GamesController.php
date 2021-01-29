<?php


namespace App\Controller\Admin;


use App\Repository\GamesRepository;
use App\Repository\WeeksRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
	/**
	 * @Route( "/admin/games", name="app_admin_games" )
	 */
	public function index( WeeksRepository $weeks_repository, GamesRepository $games_repository )
	{
		$weeks		= $weeks_repository->findBy( $orderBy = [ 'id' => 'ASC' ] );
		$week_games	= array();

		foreach ( $weeks as $week )
		{
			$week_games[ $week->getId() ] = $games_repository->findBy( ['week_id' => $week->getId() ], $orderBy = [ 'start' => 'ASC' ] );
		}
		dump( $weeks );
		return $this->render( 'admin/games/list.html.twig', [ 'week_games' => $week_games ] );
	}
}