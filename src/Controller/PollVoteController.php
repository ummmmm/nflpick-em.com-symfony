<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PollVoteController extends AbstractController
{
	/**
	 * @Route( "/polls/{id}/vote", methods="POST" )
	 */
	public function pollVote( $id )
	{
		return $this->json( [ 'success' => true ] );
	}
}