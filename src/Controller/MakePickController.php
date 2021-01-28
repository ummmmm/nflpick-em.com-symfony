<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MakePickController extends AbstractController
{
	/**
	 * @Route( "/picks/{game_id}", methods="POST" )
	 */
	public function makePick( $game_id )
	{
		return $this->json( [ 'success' => true ] );
	}
}