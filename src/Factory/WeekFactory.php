<?php

namespace App\Factory;

use App\Entity\Week;
use App\Repository\WeekRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Week|Proxy createOne(array $attributes = [])
 * @method static Week[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Week|Proxy findOrCreate(array $attributes)
 * @method static Week|Proxy random(array $attributes = [])
 * @method static Week|Proxy randomOrCreate(array $attributes = [])
 * @method static Week[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Week[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static WeekRepository|RepositoryProxy repository()
 * @method Week|Proxy create($attributes = [])
 */
final class WeekFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
			'locked' => false
        ];
    }

    protected function initialize(): self
    {
		// see https://github.com/zenstruck/foundry#initialization
		return $this
			// ->afterInstantiate(function(News $news) {})
			;
    }

    protected static function getClass(): string
    {
        return Week::class;
    }

    public function week( int $week )
	{
		$firstSunday = strtotime( 'First Sunday of September' );
		return $this->addState( [ 'date' => $firstSunday + ( 60 * 60 * 10 ) + ( ( $week - 1 ) * 60 * 60 * 24 * 7 ) ] );
	}
}
