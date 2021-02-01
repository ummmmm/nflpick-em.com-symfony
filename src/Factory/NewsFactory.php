<?php

namespace App\Factory;

use App\Entity\News;
use App\Repository\NewsRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static News|Proxy createOne(array $attributes = [])
 * @method static News[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static News|Proxy findOrCreate(array $attributes)
 * @method static News|Proxy random(array $attributes = [])
 * @method static News|Proxy randomOrCreate(array $attributes = [])
 * @method static News[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static News[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static NewsRepository|RepositoryProxy repository()
 * @method News|Proxy create($attributes = [])
 */
final class NewsFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'title' 	=> self::faker()->realText( 20 ),
			'data'		=> self::faker()->paragraphs( self::faker()->numberBetween( 1, 4 ), true ),
			'post_dt'	=> self::faker()->unixTime(),
			'ip'		=> self::faker()->boolean ? self::faker()->ipv4 : self::faker()->ipv6,
			'active'	=> self::faker()->boolean
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
        return News::class;
    }
}
