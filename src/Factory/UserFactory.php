<?php

namespace App\Factory;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @method static User|Proxy createOne(array $attributes = [])
 * @method static User[]|Proxy[] createMany(int $number, $attributes = [])
 * @method static User|Proxy findOrCreate(array $attributes)
 * @method static User|Proxy random(array $attributes = [])
 * @method static User|Proxy randomOrCreate(array $attributes = [])
 * @method static User[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static User[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static UserRepository|RepositoryProxy repository()
 * @method User|Proxy create($attributes = [])
 */
final class UserFactory extends ModelFactory
{
	private $encoder;

    public function __construct( UserPasswordEncoderInterface $encoder )
    {
        parent::__construct();

        $this->encoder = $encoder;
    }

    protected function getDefaults(): array
    {
        return [
            'email'			=> self::faker()->unique()->safeEmail,
			'roles'			=> [ 'ROLE_USER' ],
			'password'		=> self::faker()->password,
			'first_name'	=> self::faker()->firstName,
			'last_name'		=> self::faker()->lastName,
			'wins'			=> self::faker()->numberBetween( 0, 100 ),
			'losses'		=> self::faker()->numberBetween( 0, 100 ),
			'paid'			=> self::faker()->boolean,
			'current_place'	=> self::faker()->numberBetween( 1, 30 ),
			'active'		=> self::faker()->boolean,
			'message'		=> '',
			'last_on_dt'	=> time() - ( 60 * self::faker()->numberBetween( 0, 100 ) )
		];
    }

    protected function initialize(): self
    {
        // see https://github.com/zenstruck/foundry#initialization
        return $this
             ->afterInstantiate( function( User $user ) {
             	$user->setPassword( $this->encoder->encodePassword( $user, $user->getPassword() ) );
			 } )
        ;
    }

    protected static function getClass(): string
    {
        return User::class;
    }
}
