<?php

namespace App\Form;

use App\Entity\Poll;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add( 'question', TextType::class, [ 'attr' => [ 'size' => 50 ] ] )
        ;

        $builder->addEventListener( FormEvents::PRE_SET_DATA, function ( FormEvent $event )
		{
			/** @var Poll $poll */
			$poll = $event->getData();
			$form = $event->getForm();

			if ( $poll && $poll->getId() !== null )
			{
				$form->add( 'active', ChoiceType::class, [
					'choices' => [
						'Active' => true,
						'Inactive' => false
					],
					'expanded' => true
				] );
			}
		} );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Poll::class,
        ]);
    }
}
