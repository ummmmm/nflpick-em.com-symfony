<?php


namespace App\Controller;


use App\Entity\Game;
use App\Entity\Pick;
use App\Entity\User;
use App\Entity\Week;
use App\Repository\GameRepository;
use App\Repository\PickRepository;
use App\Repository\WeeksRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PickController extends AbstractController
{
	/**
	 * @Route( "/picks", name="app_picks" )
	 */
	public function index(WeekRepository $weeks_repository)
	{
		$weeks = $weeks_repository->findBy([], $orderBy = ['id' => 'ASC']);

		return $this->render('picks/index.html.twig', ['weeks' => $weeks]);
	}

	/**
	 * @Route( "/picks/week/{id}", name="app_picks_week" )
	 */
	public function weekList(Week $week, GameRepository $game_repository)
	{
		$games = $game_repository->findBy(['week' => $week], ['start' => 'ASC']);

		return $this->render('picks/week_list.html.twig', ['week' => $week, 'games' => $games]);
	}
}

