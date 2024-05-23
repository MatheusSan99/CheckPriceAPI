<?php

namespace API\CheckPrice\Domain\Entity\GasStation\UseCase;

use API\CheckPrice\Domain\Entity\Adress\AdressEntity;
use API\CheckPrice\Domain\Entity\Gas\DieselEntity;
use API\CheckPrice\Domain\Entity\Gas\EthanolEntity;
use API\CheckPrice\Domain\Entity\Gas\GnvEntity;
use API\CheckPrice\Domain\Entity\Gas\PremiumGasEntity;
use API\CheckPrice\Domain\Entity\Gas\RegularGasEntity;
use API\CheckPrice\Domain\Entity\GasStation\FlagEntity;
use API\CheckPrice\Domain\Entity\GasStation\GasStationEntity;
use API\CheckPrice\Domain\Entity\GasStation\UseCase\FlagCase;
use API\CheckPrice\Services\AddressHandler;
use Smalot\PdfParser\Document;

trait GasStationPdfHandler
{
    use AddressHandler;

    public function getGasStationListFromDocument(Document $document) : array
    {
        $arrGasStation = explode("\n", $document->getText());

        $arrGasStation = $this->mapGasStation($arrGasStation);

        return $arrGasStation;
    }

    private function mapGasStation($arrGasStation)
    {
        $mappedGasStation = [];
        $gasStation = [];

        $arrGasStation = $this->ignoreNonGasStationData($arrGasStation);

        if (empty($arrGasStation)) {
            return [];
        }

        foreach ($arrGasStation as $gasStationData) {
            if (is_numeric($gasStationData) && !empty($gasStation)) {
                $mappedGasStation[] = $this->mapGasStationData($gasStation);
            }

            $gasStation[] = $gasStationData;
        }

        return $mappedGasStation;
    }

    private function mapGasStationData($gasStation)
    {
        $FlagCase = new FlagCase();
        $id = $gasStation[0] ?? null;
        $name = $gasStation[1] ?? null;
        $address = $this->getAdressData($gasStation[2]) ?? null;
        $flagData = $gasStation[3] ?? null;
    
        if (!$id || !$name || !$address || !$flagData) {
            return null;
        }

        $flag = $FlagCase->validationForValidFlag($flagData);

        $gasData = $this->removeFlagFromGasData($flag, $gasStation[3]);

        return new GasStationEntity(
            $id,
            $name,
            new AdressEntity($address['street'], $address['number'], $address['neighborhood'], $address['city'], $address['state'], $address['zipCode']),
            new FlagEntity($flag),
            new RegularGasEntity($this->getGasPrice($gasData, 0)),
            new PremiumGasEntity($this->getGasPrice($gasData, 1)),
            new DieselEntity($this->getGasPrice($gasData, 2)),
            new EthanolEntity($this->getGasPrice($gasData, 3)),
            new GnvEntity($this->getGasPrice($gasData, 4))
        );
    }

    function getAdressData($address) 
    {
        $street = $this->getStreetFromString($address);
        
        return [
            'street' => $street,
            'neighborhood' => $this->getNeighborhoodFromString($address),
            'number' => $this->getNumberFromString($address),
            'city' => 'Joinville',
            'state' => 'SC',
            'zipCode' => ''
        ];
    }

    private function removeFlagFromGasData($flag, $gasData) 
    {
        $gasData = substr($gasData, strlen($flag . ' '));

        return explode(' ', $gasData);
    }

    private function getGasPrice($gasData, $index)
    {
        if (!isset($gasData[$index])) {
            return '0.00';
        }
        
        return $this->parseGasPrice($gasData[$index]);
    }

    private function parseGasPrice($price)
    {
        $price = str_replace(',', '.', $price);
        
        if (!is_numeric($price)) {
            return '0.00';
        }

        return str_replace(',', '.', $price);
    }
    
    private function ignoreNonGasStationData($arrGasStation)
    {
        $start = array_search('1', $arrGasStation);

        $end = array_search('99', $arrGasStation);

        return array_slice($arrGasStation, $start, $end - $start);
    }
}
