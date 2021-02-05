<?php

namespace App\Factory;

use App\Entity\PollAnswer;
use App\Repository\PollAnswerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static PollAnswer|Proxy createOne(array $attributes = [])
 * @method static PollAnswer[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static PollAnswer|Proxy findOrCreate(array $attributes)
 * @method static PollAnswer|Proxy random(array $attributes = [])
 * @method static PollAnswer|Proxy randomOrCreate(array $attributes = [])
 * @method static PollAnswer[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static PollAnswer[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PollAnswerRepository|RepositoryProxy repository()
 * @method PollAnswer|Proxy create($attributes = [])
 */
final class PollAnswerFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://github.com/zenstruck/foundry#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'answer' => self::faker()->text( 15 )
        ];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
            // ->afterInstantiate(function(PollAnswer $pollAnswer) {})
        ;
    }

    protected static function getClass(): string
    {
        return PollAnswer::class;
    }
}
