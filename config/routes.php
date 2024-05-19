<?php

use API\CheckPrice\Controller\{
    PostosController,
};
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\App;
use Slim\Psr7\Factory\ResponseFactory;

return function (App $app) {
    $app->options('/{routes:.*}', function (ServerRequestInterface $request, ResponseInterface $response, $args) {
        return $response;
    });

    $app->get('/testes', function (ServerRequestInterface $request, ResponseInterface $response) {
        $response = $response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write("API INT233EGRAÇÂO");
        return $response;
    });

    $app->get('/postos/{month}/{year}', PostosController::class . ':checkActualPrice')->setName('');

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function () use ($app) {
        $responseFactory = new ResponseFactory();
        $response = $responseFactory->createResponse(404, 'Página não existe ou não foi encontrada');
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write(json_encode(['Erro' => 'A página solicitada não existe ou não foi encontrada.']));
        return $response;
    });
};
