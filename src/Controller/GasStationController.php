<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Domain\DataSource\Services\UseCases\SearchPriceCase;
use API\CheckPrice\Infra\Government\BrasilGovernmentGasBaseAPI;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class GasStationController
{
    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $SearchPriceCase = new SearchPriceCase(new BrasilGovernmentGasBaseAPI());
            $pricesUrl = $SearchPriceCase->execute();


            // $response->getBody()->write(json_encode($pdfInText));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['Erro' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
        }
    }
}
