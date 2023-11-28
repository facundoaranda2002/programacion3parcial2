<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;


class OperadorMiddle
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol === "operador") {
                $response = $handler->handle($request);
                
            } else {
                $response = new Response();
                $payload = json_encode(array('mensaje' => 'No sos operador'));
                $response->getBody()->write($payload);
            }
        return $response;
    }
}