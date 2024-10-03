<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Domain\Service\FlagValidatorService;
use API\CheckPrice\Domain\Services\AddressService;
use API\CheckPrice\Domain\UseCases\FindGasStationListFromDocumentCase;
use API\CheckPrice\Domain\UseCases\PdfHandler\SearchPdfDataCase;
use API\CheckPrice\Domain\UseCases\SearchPriceCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class GasStationController
{
    private $pdo;

    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $SearchPriceCase = new SearchPriceCase();
            $pricesUrl = $SearchPriceCase->execute('combustiveis', $args['month'], $args['year']);
            $SearchPdfDataCase = new SearchPdfDataCase();
            $pdfInText = $SearchPdfDataCase->execute($pricesUrl);

            $FindGasStationListFromDocumentCase = new FindGasStationListFromDocumentCase(new FlagValidatorService(), new AddressService());

            $pdfInText = $FindGasStationListFromDocumentCase->execute($pdfInText);

            $response->getBody()->write(json_encode($pdfInText));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode(['Erro' => $e->getMessage()]));
            return $response->withHeader('Content-Type', 'application/json')->withStatus($e->getCode());
        }
    }
}
