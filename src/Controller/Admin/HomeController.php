<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
	/**
	 * @Route( "/admin", name="app_admin" )
	 */
	public function index()
	{
		return $this->render( 'admin/homepage/content.html.twig' );
	}
}