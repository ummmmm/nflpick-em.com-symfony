<?php


namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
	/**
	 * @Route( "/contact", name="app_contact" )
	 */
	public function new( Request $request ): Response
	{
		$contact = new Contact();

		$form = $this->createForm( ContactType::class, $contact );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			$contact = $form->getData();

			$this->addFlash( 'info', 'Your message has been sent and you should receive a response within the next 24 hours.' );
			return $this->redirectToRoute( 'app_contact' );
		}

		return $this->render( 'contact/content.html.twig', [ 'form' => $form->createView(), 'formErrors' => $form->getErrors( true, true ) ] );
	}
}
