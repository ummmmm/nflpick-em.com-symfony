<?php


namespace App\Controller\Admin;


use App\Entity\News;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
	/**
	 * @Route( "/admin/news", name="app_admin_news" )
	 * @param NewsRepository $news_repository
	 */
	public function index( NewsRepository $news_repository )
	{
		$news = $news_repository->findAll( [], $orderBy = [ 'id' => 'DESC' ] );

		return $this->render( 'admin/news/index.html.twig', [ 'news' => $news ] );
	}

	/**
	 * @Route( "/admin/news/add", name="app_admin_news_add" )
	 */
	public function add( Request $request, EntityManagerInterface $entity_manager )
	{
		$news = new News();

		$form = $this->createForm( NewsType::class, $news );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			/** @var \App\Entity\User $user */
			$user = $this->getUser();

			$news->setUser( $user );
			$news->setIp( $request->getClientIp() );
			$news->setPostDt( time() );

			$entity_manager->persist( $news );
			$entity_manager->flush();

			$this->addFlash( 'success', 'News successfully created' );

			return $this->redirectToRoute( 'app_admin_news' );
		}

		return $this->render( 'admin/news/add.html.twig', [ 'form' => $form->createView() ] );
	}

	/**
	 * @Route( "/admin/news/{id}/edit", name="app_admin_news_edit" )
	 */
	public function edit( News $news, Request $request, EntityManagerInterface $entity_manager )
	{
		$form = $this->createForm( NewsType::class, $news );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			/** @var \App\Entity\User $user */
			$user = $this->getUser();

			$news->setUser( $user );
			$news->setIp( $request->getClientIp() );

			$entity_manager->persist( $news );
			$entity_manager->flush();

			$this->addFlash( 'success', 'News successfully updated' );

			return $this->redirectToRoute( 'app_admin_news_edit', [ 'id' => $news->getId() ] );
		}

		return $this->render( 'admin/news/edit.html.twig', [ 'form' => $form->createView(), 'news' => $news ] );
	}

	/**
	 * @Route( "/admin/news/{id}/delete", name="app_admin_news_delete", methods="POST" )
	 */
	public function delete( News $news, EntityManager $entity_manager )
	{
		$entity_manager->remove( $news );
		$entity_manager->flush();
	}
}