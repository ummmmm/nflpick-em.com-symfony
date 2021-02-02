<?php

namespace App\Repository;

use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry )
    {
        parent::__construct($registry, Team::class);
    }

    public function insertAll()
	{
		$teams = array();

		array_push( $teams, array( 'name' => 'Buffalo Bills', 			'conference' => 'AFC East',		'stadium' => 'New Era Field' ) );
		array_push( $teams, array( 'name' => 'Miami Dolphins', 			'conference' => 'AFC East',		'stadium' => 'Hard Rock Stadium' ) );
		array_push( $teams, array( 'name' => 'New England Patriots', 		'conference' => 'AFC East',		'stadium' => 'Gillette Stadium' ) );
		array_push( $teams, array( 'name' => 'New York Jets', 			'conference' => 'AFC East',		'stadium' => 'MetLife Stadium' ) );

		array_push( $teams, array( 'name' => 'Baltimore Ravens', 			'conference' => 'AFC North', 	'stadium' => 'M&T Bank Stadium' ) );
		array_push( $teams, array( 'name' => 'Carolina Panthers', 		'conference' => 'AFC North', 	'stadium' => 'Bank of America Stadium' ) );
		array_push( $teams, array( 'name' => 'Cincinnati Bengals', 		'conference' => 'AFC North', 	'stadium' => 'Paul Brown Stadium' ) );
		array_push( $teams, array( 'name' => 'Cleveland Browns', 			'conference' => 'AFC North', 	'stadium' => 'FirstEnergy Stadium' ) );
		array_push( $teams, array( 'name' => 'Pittsburgh Steelers', 		'conference' => 'AFC North', 	'stadium' => 'Heinz Field' ) );

		array_push( $teams, array( 'name' => 'Houston Texans', 			'conference' => 'AFC South', 	'stadium' => 'NRG Stadium' ) );
		array_push( $teams, array( 'name' => 'Indianapolis Colts', 		'conference' => 'AFC South', 	'stadium' => 'Lucas Oil Stadium' ) );
		array_push( $teams, array( 'name' => 'Jacksonville Jaguars', 		'conference' => 'AFC South', 	'stadium' => 'TIAA Bank Field' ) );
		array_push( $teams, array( 'name' => 'Tennessee Titans', 			'conference' => 'AFC South', 	'stadium' => 'Nissan Field' ) );

		array_push( $teams, array( 'name' => 'Arizona Cardinals', 		'conference' => 'AFC West', 	'stadium' => 'State Farm Field' ) );
		array_push( $teams, array( 'name' => 'Denver Broncos', 			'conference' => 'AFC West', 	'stadium' => 'Empower Field at Mile High' ) );
		array_push( $teams, array( 'name' => 'Kansas City Chiefs', 		'conference' => 'AFC West', 	'stadium' => 'Arrowhead Stadium' ) );
		array_push( $teams, array( 'name' => 'Las Vegas Raiders', 		'conference' => 'AFC West', 	'stadium' => 'Allegiant Stadium' ) );
		array_push( $teams, array( 'name' => 'Los Angeles Chargers', 		'conference' => 'AFC West', 	'stadium' => 'SoFi Stadium' ) );

		array_push( $teams, array( 'name' => 'Dallas Cowboys', 			'conference' => 'NFC East', 	'stadium' => 'AT&T Stadium' ) );
		array_push( $teams, array( 'name' => 'New York Giants', 			'conference' => 'NFC East', 	'stadium' => 'MetLife Stadium' ) );
		array_push( $teams, array( 'name' => 'Philadelphia Eagles', 		'conference' => 'NFC East', 	'stadium' => 'Lincoln Financial Field' ) );
		array_push( $teams, array( 'name' => 'Washington Football Team',	'conference' => 'NFC East', 	'stadium' => 'FedExField' ) );

		array_push( $teams, array( 'name' => 'Chicago Bears', 			'conference' => 'NFC North', 	'stadium' => 'Soldier Field' ) );
		array_push( $teams, array( 'name' => 'Detroit Lions', 			'conference' => 'NFC North', 	'stadium' => 'Ford Field' ) );
		array_push( $teams, array( 'name' => 'Green Bay Packers', 		'conference' => 'NFC North', 	'stadium' => 'Lambeau Field' ) );
		array_push( $teams, array( 'name' => 'Minnesota Vikings', 		'conference' => 'NFC North', 	'stadium' => 'U.S. Bank Stadium' ) );

		array_push( $teams, array( 'name' => 'Atlanta Falcons', 			'conference' => 'NFC South', 	'stadium' => 'Mercedes-Benz Stadium' ) );
		array_push( $teams, array( 'name' => 'New Orleans Saints', 		'conference' => 'NFC South', 	'stadium' => 'Mercedes-Benz Superdome' ) );
		array_push( $teams, array( 'name' => 'Tampa Bay Buccaneers', 		'conference' => 'NFC South', 	'stadium' => 'Raymond James Stadium' ) );
		array_push( $teams, array( 'name' => 'San Francisco 49ers', 		'conference' => 'NFC West', 	'stadium' => 'Levi\'s Stadium' ) );
		array_push( $teams, array( 'name' => 'Seattle Seahawks', 			'conference' => 'NFC West', 	'stadium' => 'Lumen Field' ) );
		array_push( $teams, array( 'name' => 'Los Angeles Rams', 			'conference' => 'NFC West', 	'stadium' => 'SoFi Stadium' ) );

		foreach ( $teams as $entry )
		{
			$team = new Team();
			$team->setName( $entry[ 'name' ] );
			$team->setConference( $entry[ 'conference' ] );
			$team->setStadium( $entry[ 'stadium' ] );

			$this->getEntityManager()->persist( $team );
		}

		$this->getEntityManager()->flush();
	}

    // /**
    //  * @return Team[] Returns an array of Team objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Team
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
