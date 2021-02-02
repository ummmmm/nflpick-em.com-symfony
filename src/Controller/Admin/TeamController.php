<?php


namespace App\Controller\Admin;


use App\Entity\Team;
use App\Form\TeamType;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
	/**
	 * @Route( "/admin/teams", name="app_admin_teams" )
	 */
	public function index( TeamRepository $teamRepository )
	{
		$teams = $teamRepository->findBy( [], [ 'name' => 'ASC' ] );

		return $this->render( 'admin/teams/index.html.twig', [ 'teams' => $teams ] );
	}

	/**
	 * @Route( "/admin/teams/{id}/edit", name="app_admin_teams_edit" )
	 */
	public function editTeam( Team $team, Request $request, EntityManagerInterface $entityManager )
	{
		$form = $this->createForm( TeamType::class, $team );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			$entityManager->persist( $team );
			$entityManager->flush();

			$this->addFlash( 'success', 'Team successfully updated' );

			return $this->redirectToRoute( 'app_admin_teams_edit', [ 'id' => $team->getId() ] );
		}

		return $this->render( 'admin/teams/edit.html.twig', [ 'team' => $team, 'form' => $form->createView() ] );
	}
}
