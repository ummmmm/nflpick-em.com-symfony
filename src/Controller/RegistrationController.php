<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
	/**
	* @Route("/register", name="app_register")
	*/
	public function register( Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager ): Response
	{
		if ( $this->getUser() !== null )
		{
			return $this->redirectToRoute( 'app_homepage' );
		}

		$user = new User();
		$form = $this->createForm( RegistrationFormType::class, $user );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			$user->setPassword( $passwordEncoder->encodePassword( $user, $form->get('plainPassword')->getData() ) );
			$user->setLastOnDt( time() );

			$entityManager->persist($user);
			$entityManager->flush();

			return $guardHandler->authenticateUserAndHandleSuccess( $user, $request, $authenticator, 'main' );
		}

		return $this->render( 'registration/register.html.twig', [ 'registrationForm' => $form->createView(), 'formErrors' => $form->getErrors( true, true ) ] );
	}
}
