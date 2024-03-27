<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Services\ConnectionHandler;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class PostosController
{
    private $pdo;
    use ConnectionHandler;


    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function index (ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode($this->searchPrices()));
        return $response;
    }

    
}
