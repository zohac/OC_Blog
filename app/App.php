<?php
namespace app;

use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;
use \GuzzleHttp\Psr7\Response;

/**
 * [App description]
 */
class App
{

    public function run(ServerRequestInterface $request): ResponseInterface
    {
        $reponse = new Response();
        $reponse->getbody()->write('bonjour');
        return $reponse;
    }
}
