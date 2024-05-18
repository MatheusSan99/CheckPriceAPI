<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Services\ConnectionHandler;
use API\CheckPrice\Services\ParamsValidation\ParamsValidation;
use API\CheckPrice\Services\PdfHandler;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class PostosController
{
    private $pdo;
    use ConnectionHandler;
    use ParamsValidation;
    use PdfHandler;

    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        $params = $request->getQueryParams();

        $this->validateMonth($params['month']);
        $this->validateYear($params['year']);
        
        $pricesUrl = $this->searchPrices('combustiveis', $params['month'], $params['year']);

        if (empty($pricesUrl)) {
            throw new \Exception('Tipo de pesquisa não encontrado', 404);
        }

        $pdf = $this->pdfReader($pricesUrl);

        $response->getBody()->write(json_encode($pdf));

        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    }

    private function pdfReader($pricesUrl) 
    {
        $pdf = $this->searchDataPDF($pricesUrl);

        return $pdf;
    }

    
}
