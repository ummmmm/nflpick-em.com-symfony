<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
			->add( 'first_name', TextType::class, [
				'constraints' => [
					new NotBlank( [ 'message' => 'Please enter a first name' ] )
				]
			] )
			->add( 'last_name', TextType::class, [
				'constraints' => [
					new NotBlank( [ 'message' => 'Please enter a last name' ] )
				]
			] )
            ->add('email', RepeatedType::class, [
            	'type' => EmailType::class,
				'constraints' => [
					new Email( [ 'message' => 'Please enter a valid email address' ] )
				],
				'invalid_message' => 'Email addresses do not match',
				'required' => true,
				'first_options' => [ 'label' => 'Email' ],
				'second_options' => [ 'label' => 'Confirm Email' ]
			] )
            ->add('plainPassword', RepeatedType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
				'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Passwords do not match',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
				'first_options' => [ 'label' => 'Password' ],
				'second_options' => [ 'label' => 'Confirm Password' ]
            ] );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
