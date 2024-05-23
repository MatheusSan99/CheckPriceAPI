<?php

namespace API\CheckPrice\Services;

use API\CheckPrice\Domain\Entity\GasStation\UseCase\GasStationPdfHandler;

trait ConnectionHandler
{
    use GasStationPdfHandler;
    
    public function searchPrices($type, $month, $year) 
    {
        return $this->defineUrlByType($type, new DateHandler($month, $year));
    }

    private function defineUrlByType($type, DateHandler $DateHandler) 
    {
        $url = 'https://www.joinville.sc.gov.br/wp-content/uploads/' . $DateHandler->getYear() . '/0' . $DateHandler->getNumericMonth() . '/';

        switch ($type) {
            case 'pescado':
                $url .= 'Pesquisa-de-Precos-Pescados-' . $DateHandler->getMonth() . $DateHandler->getYear() . '.pdf';
                break;
            case 'churrasco':
                $url .= 'Pesquisa-de-Precos-Churrasco-' . $DateHandler->getMonth() . $DateHandler->getYear() . '.pdf';
                break;
            case 'cestaBasica':
                $url .= 'Pesquisa-de-Precos-Cesta-Basica-' . $DateHandler->getMonth() . $DateHandler->getYear() . '.pdf';
                break;
            case 'combustiveis':
                $url .= 'Pesquisa-Precos-Combustiveis-' . $DateHandler->getMonth() . $DateHandler->getYear() . '.pdf';
                break;
            case 'gasCozinha':
                $url .= 'Pesquisa-Precos-Gas-de-Cozinha-' . $DateHandler->getMonth() . $DateHandler->getYear() . '.pdf';
                break;
            default:
                $url = null;
                break;
        }
        return $url;
    }
}
