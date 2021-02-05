<?php

namespace App\Factory;

use App\Entity\PollVote;
use App\Repository\PollVoteRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static PollVote|Proxy createOne(array $attributes = [])
 * @method static PollVote[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static PollVote|Proxy findOrCreate(array $attributes)
 * @method static PollVote|Proxy random(array $attributes = [])
 * @method static PollVote|Proxy randomOrCreate(array $attributes = [])
 * @method static PollVote[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static PollVote[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PollVoteRepository|RepositoryProxy repository()
 * @method PollVote|Proxy create($attributes = [])
 */
final class PollVoteFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'date'	=> self::faker()->unixTime,
			'ip'	=> self::faker()->boolean ? self::faker()->ipv4 : self::faker()->ipv6
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(PollVote $pollVote) {})
        ;
    }

    protected static function getClass(): string
    {
        return PollVote::class;
    }
}
