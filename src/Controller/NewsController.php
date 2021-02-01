<?php


namespace App\Controller;


use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
	/**
	 * @Route( "/news", name="app_news" )
	 * @param NewsRepository $news_repository
	 * @return Response
	 */
	public function index( NewsRepository $news_repository ): Response
	{
		$news = $news_repository->findBy( [], [ 'id' => 'DESC' ] );

		return $this->render( 'news/content.html.twig', [ 'news' => $news, 'limited' => false ] );
	}
}
