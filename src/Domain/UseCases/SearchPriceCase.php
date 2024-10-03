<?php

namespace API\CheckPrice\Domain\UseCases;

use API\CheckPrice\Application\ParamsValidation\ParamsValidation;
use API\CheckPrice\Domain\ValueObjects\DateValueObject;

class SearchPriceCase
{
    
    public function execute($type, $month, $year) 
    {
        $ParamsValidation = new ParamsValidation();
        $ParamsValidation->validateMonth($month);
        $ParamsValidation->validateYear($year);

        return $this->defineUrlByType($type, new DateValueObject($month, $year));
    }

    private function defineUrlByType($type, DateValueObject $dateValueObject) 
    {
        $url = 'https://www.joinville.sc.gov.br/wp-content/uploads/' . $dateValueObject->getYear() . '/0' . $dateValueObject->getNumericMonth() . '/';

        switch ($type) {
            case 'pescado':
                $url .= 'Pesquisa-de-Precos-Pescados-' . $dateValueObject->getMonth() . $dateValueObject->getYear() . '.pdf';
                break;
            case 'churrasco':
                $url .= 'Pesquisa-de-Precos-Churrasco-' . $dateValueObject->getMonth() . $dateValueObject->getYear() . '.pdf';
                break;
            case 'cestaBasica':
                $url .= 'Pesquisa-de-Precos-Cesta-Basica-' . $dateValueObject->getMonth() . $dateValueObject->getYear() . '.pdf';
                break;
            case 'combustiveis':
                $url .= 'Pesquisa-Precos-Combustiveis-' . $dateValueObject->getMonth() . $dateValueObject->getYear() . '.pdf';
                break;
            case 'gasCozinha':
                $url .= 'Pesquisa-Precos-Gas-de-Cozinha-' . $dateValueObject->getMonth() . $dateValueObject->getYear() . '.pdf';
                break;
            default:
            throw new \Exception('Type not found', 400);
                break;
        }
        return $url;
    }
}
