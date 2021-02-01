<?php

namespace App\Factory;

use App\Entity\Game;
use App\Entity\Team;
use App\Entity\Week;
use App\Repository\GameRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Game|Proxy createOne(array $attributes = [])
 * @method static Game[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Game|Proxy findOrCreate(array $attributes)
 * @method static Game|Proxy random(array $attributes = [])
 * @method static Game|Proxy randomOrCreate(array $attributes = [])
 * @method static Game[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Game[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GameRepository|RepositoryProxy repository()
 * @method Game|Proxy create($attributes = [])
 */
final class GameFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://github.com/zenstruck/foundry#model-factories)
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Game $game) {})
        ;
    }

    protected static function getClass(): string
    {
        return Game::class;
    }

	public function homeTeam( Team $team )
	{
		return $this->addState( [ 'home' => $team ] );
	}

	public function awayTeam( Team $team )
	{
		return $this->addState( [ 'away' => $team ] );
	}

	public function week( Week $week )
	{
		return $this->addState( [ 'week' => $week, 'start' => $week->getDate() + self::faker()->numberBetween( 0, 60 * 60 * 36 ) ] );
	}
}
