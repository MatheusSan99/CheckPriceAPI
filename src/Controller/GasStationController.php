<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Domain\DataSource\Services\UseCases\SearchPriceCase;
use API\CheckPrice\Infra\Government\BrasilGovernmentGasBaseAPI;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;
use API\CheckPrice\Domain\GasStation\UseCases\GetListOfPricesCase;
use Psr\Log\LoggerInterface;

class GasStationController
{
    private LoggerInterface $logger;
    private GetListOfPricesCase $getListOfPricesCase;

    public function __construct(GetListOfPricesCase $getListOfPricesCase,  LoggerInterface $logger)
    {
        $this->logger = $logger;
        $this->getListOfPricesCase = $getListOfPricesCase;
    }

    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $this->logger->info('Check actual price request received.');
            $limit = $request->getQueryParams()['limit'] ?? 100;
            $offset = $request->getQueryParams()['offset'] ?? 1;
            $cachedResults = $this->getListOfPricesCase->execute($limit, $offset);

            if (empty($cachedResults)) {
                $SearchPriceCase = new SearchPriceCase(new BrasilGovernmentGasBaseAPI());
                $SearchPriceCase->execute();
                $cachedResults = $this->getListOfPricesCase->execute($limit, $offset);
            }

            if (empty($cachedResults)) {
                throw new \Exception('No prices found', 404);
            }

            $this->logger->info('Prices retrieved successfully.');

            $compressed = gzencode(json_encode($cachedResults));

            $response->getBody()->write($compressed);
            
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Content-Encoding', 'gzip')
                ->withStatus(200);        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['Erro' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
        }
    }

    public function checkActualPriceByZipCode(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $this->logger->info('Check actual price by zip code request received.');

            $zipCode = $args['zipCode'] ?? null;

            if (empty($zipCode)) {
                throw new \Exception('Zip code is required', 400);
            }

            $cachedResults = $this->getListOfPricesCase->execute($zipCode);

            if (empty($cachedResults)) {
                throw new \Exception('No prices found for the provided zip code', 404);
            }

            $this->logger->info('Prices retrieved successfully for zip code: ' . $zipCode);

            $response->getBody()->write(json_encode($cachedResults));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['Erro' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
        }
    }
}
