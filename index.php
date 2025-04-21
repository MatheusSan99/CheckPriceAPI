<?php

use DI\ContainerBuilder;
use Psr\Http\Message\ResponseInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

require __DIR__ . '/vendor/autoload.php';

session_start();

$containerBuilder = new ContainerBuilder();

$settings = require __DIR__ . '/config/configs.php';
$settings($containerBuilder);

$dependencies = require __DIR__ . '/config/dependencies.php';
$dependencies($containerBuilder);

$container = $containerBuilder->build();

AppFactory::setContainer($container);

$app = AppFactory::create();

$loggedInMiddleware = function ($request, $handler): ResponseInterface {
    $routeContext = RouteContext::fromRequest($request);
    $route = $routeContext->getRoute();

    if (empty($route)) {
        throw new HttpNotFoundException($request, $response);
    }

    $routeName = $route->getName();

    $publicRoutesArray = array('', 'login');

    if (empty($_SESSION['user']) && (!in_array($routeName, $publicRoutesArray))) {
        $routeParser = $routeContext->getRouteParser();
        $url = $routeParser->urlFor('login');

        $response = new \Slim\Psr7\Response();

        return $response->withStatus(401);
    } else {
        $response = $handler->handle($request);

        return $response;
    }
};
$app->add($loggedInMiddleware);
$app->addRoutingMiddleware();

$errorSetting = true; 
$app->addErrorMiddleware($errorSetting, true, true);

$routes = require __DIR__ . '/config/routes.php';
$routes($app);

$app->run();
