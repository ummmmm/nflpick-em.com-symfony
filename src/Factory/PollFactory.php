<?php

namespace App\Factory;

use App\Entity\Poll;
use App\Repository\PollRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static Poll|Proxy createOne(array $attributes = [])
 * @method static Poll[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static Poll|Proxy findOrCreate(array $attributes)
 * @method static Poll|Proxy random(array $attributes = [])
 * @method static Poll|Proxy randomOrCreate(array $attributes = [])
 * @method static Poll[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Poll[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PollRepository|RepositoryProxy repository()
 * @method Poll|Proxy create($attributes = [])
 */
final class PollFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'question' => self::faker()->text( 30 ),
			'post_dt' => self::faker()->unixTime,
			'active' => self::faker()->boolean
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(Poll $poll) {})
        ;
    }

    protected static function getClass(): string
    {
        return Poll::class;
    }
}
