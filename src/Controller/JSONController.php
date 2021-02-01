<?php


namespace App\Controller;


use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class JSONController extends AbstractController
{
	private $serializer;

	public function __construct( SerializerInterface $serializer )
	{
		$this->serializer = $serializer;
	}

	protected function jsonSuccess( $data = null )
	{
		return $this->jsonLowLevel( [ 'success' => true, 'data' => $data ] );
	}

	protected function jsonFailure( string $error_code, string $error_message )
	{
		return $this->jsonLowLevel( [ 'success' => false, 'error_code' => $error_code, 'error_message' => $error_message ] );
	}

	private function jsonLowLevel( $data, $status = 200, $headers = [] )
	{
		$context = new SerializationContext();
		$context->setSerializeNull( true );

		return new JsonResponse( $this->serializer->serialize( $data, 'json', $context ), 200, [], true );
	}
}
