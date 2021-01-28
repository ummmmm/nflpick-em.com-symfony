<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
    	if ( $this->getUser() )
		{
			return $this->redirectToRoute( 'app_homepage' );
		}

        $error			= $authenticationUtils->getLastAuthenticationError();
        $last_username	= $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [ 'last_username' => $last_username, 'error' => $error ] );
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException( 'This method can be blank - it will be intercepted by the logout key on your firewall.' );
    }
}