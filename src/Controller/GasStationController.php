<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Services\ConnectionHandler;
use API\CheckPrice\Services\ParamsValidation\ParamsValidation;
use API\CheckPrice\Services\GasStationPdfHandler;
use API\CheckPrice\Services\PdfHandler\PdfHandler;
use API\CheckPrice\Services\PdfHandler\PdfHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class GasStationController implements PdfHandlerInterface
{
    private $pdo;
    use ConnectionHandler;
    use ParamsValidation;
    use GasStationPdfHandler;
    use PdfHandler;

    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $this->validateMonth($args['month']);
            $this->validateYear($args['year']);
            
            $pricesUrl = $this->searchPrices('combustiveis', $args['month'], $args['year']);
            
            if (empty($pricesUrl)) {
                throw new \Exception('Tipo de pesquisa não encontrado', 404);
            }

            $gastStationList = $this->pdfReader($pricesUrl);

            $response->getBody()->write(json_encode($gastStationList));

            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['Erro' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
        }
    }

    public function pdfReader(string $pricesUrl) : array
    {
        $document = $this->searchDataPDF($pricesUrl);

        $gastStationList = $this->getGasStationListFromDocument($document);

        return $gastStationList;
    }

    
}
