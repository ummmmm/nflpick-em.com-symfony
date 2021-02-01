<?php

namespace App\Factory;

use App\Entity\Game;
use App\Entity\Pick;
use App\Entity\User;
use App\Repository\PickRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Pick|Proxy createOne(array $attributes = [])
 * @method static Pick[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Pick|Proxy findOrCreate(array $attributes)
 * @method static Pick|Proxy random(array $attributes = [])
 * @method static Pick|Proxy randomOrCreate(array $attributes = [])
 * @method static Pick[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Pick[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PickRepository|RepositoryProxy repository()
 * @method Pick|Proxy create($attributes = [])
 */
final class PickFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
           'ip' => self::faker()->boolean ? self::faker()->ipv4 : self::faker()->ipv6
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Pick $pick) {})
        ;
    }

    protected static function getClass(): string
    {
        return Pick::class;
    }

    public function user( User $user )
	{
		return $this->addState( [ 'user' => $user ] );
	}

	public function game( Game $game )
	{
		if ( self::faker()->boolean )
		{
			$winner = $game->getHome();
			$loser	= $game->getAway();
		}
		else
		{
			$winner = $game->getAway();
			$loser	= $game->getHome();
		}

		return $this->addState( [ 'game' => $game, 'week' => $game->getWeek(), 'winner' => $winner, 'loser' => $loser ] );
	}
}
