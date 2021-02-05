<?php


namespace App\Controller\Admin;


use App\Entity\Poll;
use App\Form\PollType;
use App\Repository\PollRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PollController extends AbstractController
{
	/**
	 * @Route( "/admin/polls", name="app_admin_polls" )
	 */
	public function list( PollRepository $pollRepository )
	{
		$polls = $pollRepository->findBy( [], [ 'id' => 'DESC' ] );

		return $this->render( 'admin/polls/list.html.twig', [ 'polls' => $polls ] );
	}

	/**
	 * @Route( "/admin/polls/add", name="app_admin_polls_add" )
	 */
	public function add( Request $request, EntityManagerInterface $entityManager)
	{
		$poll = new Poll();
		$form = $this->createForm( PollType::class, $poll );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			$poll->setActive( true );
			$poll->setPostDt( time() );

			$entityManager->persist( $poll );
			$entityManager->flush();

			$this->addFlash( 'success', 'Poll successfully added' );

			return $this->redirectToRoute( 'app_admin_polls' );
		}

		return $this->render( 'admin/polls/add.html.twig', [ 'form' => $form->createView(), 'formErrors' => $form->getErrors( true, true ) ] );
	}

	/**
	 * @Route( "/admin/polls/{id}/edit", name="app_admin_polls_edit" )
	 */
	public function edit( Poll $poll, Request $request, EntityManagerInterface $entityManager )
	{
		$form = $this->createForm( PollType::class, $poll );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			$entityManager->persist( $poll );
			$entityManager->flush();

			$this->addFlash( 'success', 'Poll successfully updated' );

			return $this->redirectToRoute( 'app_admin_polls_edit', [ 'id' => $poll->getId() ] );
		}

		return $this->render( 'admin/polls/edit.html.twig', [ 'poll' => $poll, 'form' => $form->createView(), 'formErrors' => $form->getErrors( true, true ) ] );
	}
}
