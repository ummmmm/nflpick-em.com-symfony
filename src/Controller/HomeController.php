<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route( "/", name="app_homepage" )
	 * @param NewsRepository $news_repository
	 * @return Response
	 */
    public function index( NewsRepository $news_repository ): Response
	{
		$news = $news_repository->findBy( [], [ 'id' => 'DESC' ] );

    	return $this->render( 'news/content.html.twig', [ 'news' => $news, 'limited' => true ] );
    }
}
