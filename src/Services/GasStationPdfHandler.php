<?php

namespace API\CheckPrice\Services;

use API\CheckPrice\Domain\Entity\Adress\AdressEntity;
use API\CheckPrice\Domain\Entity\Gas\DieselEntity;
use API\CheckPrice\Domain\Entity\Gas\EthanolEntity;
use API\CheckPrice\Domain\Entity\Gas\GnvEntity;
use API\CheckPrice\Domain\Entity\Gas\PremiumGasEntity;
use API\CheckPrice\Domain\Entity\Gas\RegularGasEntity;
use API\CheckPrice\Domain\Entity\GasStation\FlagEntity;
use API\CheckPrice\Domain\Entity\GasStation\GasStationEntity;
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

        $arrGasStation = $this->ignoreNonGasStationData($arrGasStation);

        $gasStation = [];

        foreach ($arrGasStation as $key => $value) {
            if (is_numeric($value)) {
                if (!empty($gasStation)) {
                    $mappedGasStation[] = $this->mapGasStationData($gasStation);
                    $gasStation = [];
                }
            }

            $gasStation[] = $value;
        }


        return $mappedGasStation;
    }

    private function mapGasStationData($gasStation)
    {
        $id = $gasStation[0] ?? null;
        $name = $gasStation[1] ?? null;
        $address = $this->getAdressData($gasStation[2]) ?? null;
        $flagData = $gasStation[3] ?? null;
    
        if (!$id || !$name || !$address || !$flagData) {
            return null;
        }

        $flag = $this->getFlag($flagData);

        $gasData = $this->removeFlagFromGasData($flag, $gasStation[3]);

        return new GasStationEntity(
            $id,
            $name,
            new AdressEntity($address['street'], $address['number'], $address['neighborhood'], $address['city'], $address['state']),
            new FlagEntity($flag),
            new RegularGasEntity($this->getGasPrice($gasData, 0)),
            new PremiumGasEntity($this->getGasPrice($gasData, 1)),
            new DieselEntity($this->getGasPrice($gasData, 2)),
            new EthanolEntity($this->getGasPrice($gasData, 3)),
            new GnvEntity($this->getGasPrice($gasData, 4))
        );
    }

    private function getFlag($flagData)
    {
        if (strpos($flagData, ' ') === false) {
            return $flagData;
        }

        return substr($flagData, 0, strpos($flagData, ' ', strpos($flagData, ' ') + 1));
    }

    function getAdressData($address) 
    {
        return [
            'street' => $this->getStreetFromString($address),
            'neighborhood' => $this->getNeighborhoodFromString($address),
            'number' => $this->getNumberFromString($address),
            'city' => 'Joinville',
            'state' => 'SC',
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
            return 0;
        }
        
        return $this->parseGasPrice($gasData[$index]);
    }

    private function parseGasPrice($price)
    {
        if (!is_numeric($price)) {
            return 0;
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
