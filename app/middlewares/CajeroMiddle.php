<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class CajeroMiddle
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol === "cajero") {
                $response = $handler->handle($request);
                
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos cajero'));
                $response->getBody()->write($payload);
            }
        return $response;
    }
}