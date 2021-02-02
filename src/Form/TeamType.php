<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeamType extends AbstractType
{
	private const NFC_NORTH	= 'NFC North';
	private const NFC_EAST	= 'NFC East';
	private const NFC_SOUTH	= 'NFC South';
	private const NFC_WEST	= 'NFC West';
	private const AFC_NORTH	= 'AFC North';
	private const AFC_EAST	= 'AFC East';
	private const AFC_SOUTH = 'AFC South';
	private const AFC_WEST	= 'AFC West';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [ 'attr' => [ 'size' => 50 ] ] )
			->add('stadium', TextType::class, [ 'attr' => [ 'size' => 50 ] ] )
            ->add('conference', ChoiceType::class, [
            	'choices' => [
					self::NFC_NORTH	=> self::NFC_NORTH,
					self::NFC_EAST	=> self::NFC_EAST,
					self::NFC_SOUTH	=> self::NFC_SOUTH,
					self::NFC_WEST	=> self::NFC_WEST,
					self::AFC_NORTH	=> self::AFC_NORTH,
					self::AFC_EAST	=> self::AFC_EAST,
					self::AFC_SOUTH	=> self::AFC_SOUTH,
					self::AFC_WEST	=> self::AFC_WEST
				]
			] )
			->add( 'submit', SubmitType::class )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
        ]);
    }
}
