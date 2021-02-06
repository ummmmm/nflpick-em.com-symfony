<?php


namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
	/**
	 * @Route( "/contact", name="app_contact" )
	 */
	public function new( Request $request, MailerInterface $mailer): Response
	{
		$contact	= new Contact();
		$form		= $this->createForm( ContactType::class, $contact );
		$form->handleRequest( $request );

		if ( $form->isSubmitted() && $form->isValid() )
		{
			/** @var Contact $contact */
			$contact	= $form->getData();
			$email		= ( new Email() )
				->from( new Address( 'contact@nflpick-em.com', 'NFL Pick-Em' ) )
				->to( $this->getParameter( 'app.contact_email'  ) )
				->replyTo( new Address( $contact->getEmail(), $contact->getName() ) )
				->subject( $contact->getSubject() === null ? 'No Subject' : $contact->getSubject() )
				->text( "Name: " . $contact->getName() . "\n" . "Email: " . $contact->getEmail() . "\n" . "Message: " . $contact->getMessage() );

			$mailer->send( $email );

			$this->addFlash( 'info', 'Your message has been sent and you should receive a response within the next 24 hours.' );
			return $this->redirectToRoute( 'app_contact' );
		}

		return $this->render( 'contact.html.twig', [ 'form' => $form->createView(), 'formErrors' => $form->getErrors( true, true ) ] );
	}
}
