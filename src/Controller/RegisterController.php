<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route( "/register", name="app_register" )
     */
    public function content()
    {
        return $this->render( 'register/content.html.twig', [] );
    }
}