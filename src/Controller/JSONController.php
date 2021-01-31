<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class JSONController extends AbstractController
{
	protected function jsonSuccess( $data = null )
	{
		return $this->json( [ 'success' => true, 'data' => $data ] );
	}

	protected function jsonFailure( string $error_code, string $error_message )
	{
		return $this->json( [ 'success' => false, 'error_code' => $error_code, 'error_message' => $error_message ] );
	}
}
