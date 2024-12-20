<?php

namespace API\CheckPrice\Controller;

use API\CheckPrice\Application\ParamsValidation\DateValidation\MonthValidation;
use API\CheckPrice\Application\ParamsValidation\DateValidation\YearValidation;
use API\CheckPrice\Domain\DataSource\Factories\TypeSearchFactory;
use API\CheckPrice\Domain\DataSource\Services\UseCases\SearchPriceCase;
use API\CheckPrice\Domain\GasStation\Services\FlagValidatorService;
use API\CheckPrice\Domain\GasStation\Services\UseCases\FindGasStationListFromDocumentCase;
use API\CheckPrice\Domain\Generic\Services\AddressService;
use API\CheckPrice\Domain\Generic\ValueObjects\Date\DateValueObject;
use API\CheckPrice\Domain\Pdf\Services\UseCases\SearchPdfDataCase;
use API\CheckPrice\Infra\Government\JoinvilleGovernmentBaseAPI;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Psr7\Response;

class GasStationController
{
    public function checkActualPrice(ServerRequestInterface $request, Response $response, array $args): Response
    {
        try {
            $DateValueObject = new DateValueObject(MonthValidation::validate($args['month']), YearValidation::validate($args['year']));
            $SearchPriceCase = new SearchPriceCase(new JoinvilleGovernmentBaseAPI($DateValueObject), TypeSearchFactory::create('combustiveis', $DateValueObject));
            $pricesUrl = $SearchPriceCase->execute();
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
