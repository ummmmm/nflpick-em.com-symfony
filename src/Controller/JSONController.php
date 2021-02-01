<?php


namespace App\Controller;


use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class JSONController
{
	private $serializer;

	public function __construct( SerializerInterface $serializer )
	{
		$this->serializer = $serializer;
	}

	protected function jsonSuccess( $data = null )
	{
		return new JsonResponse( $this->serializer->serialize( [ 'success' => true, 'data' => $data ], 'json' ), 200, [], true );
	}

	protected function jsonFailure( string $error_code, string $error_message )
	{
		return new JsonResponse( $this->serializer->serialize( [ 'success' => false, 'error_code' => $error_code, 'error_message' => $error_message ], 'json' ), 200, [], true );
	}
}
