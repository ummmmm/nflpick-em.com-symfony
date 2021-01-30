<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class OnlineController extends AbstractController
{
	/**
	 * @Route( "/online", name="app_online" )
	 */
	public function content( UserRepository $user_repository )
	{
		$minutes_threshold	= 30;
		$online_users		= $user_repository->findByLastOnlineWithin( $minutes_threshold );

		return $this->render( 'online.html.twig', [ 'online_users' => $online_users, 'minutes_threshold' => $minutes_threshold ] );
	}
}
