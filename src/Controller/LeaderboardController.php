<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
	/**
	 * @Route( "/leaderboard", name="app_leaderboard" )
	 * @param UserRepository $user_repository
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function show( UserRepository $user_repository ): Response
	{
		$users = $user_repository->findBy( [], [ 'current_place' => 'ASC' ] );

		return $this->render( 'leaderboard/content.html.twig', [ 'users' => $users ] );
	}
}