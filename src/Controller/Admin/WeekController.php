<?php


namespace App\Controller\Admin;


use App\Repository\WeekRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WeekController extends AbstractController
{
	/**
	 * @Route( "/admin/weeks", name="app_admin_weeks" )
	 */
	public function index( WeekRepository $weekRepository )
	{
		$weeks = $weekRepository->findAllOrderByWeek();

		return $this->render( 'admin/weeks/index.html.twig', [ 'weeks' => $weeks ] );
	}
}
