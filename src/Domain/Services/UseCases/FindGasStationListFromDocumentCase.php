<?php

namespace API\CheckPrice\Domain\UseCases;

use API\CheckPrice\Domain\Service\FlagValidatorService;
use API\CheckPrice\Domain\Services\AddressService;
use API\CheckPrice\Domain\ValueObjects\Address\AddressValueObject;
use API\CheckPrice\Domain\ValueObjects\Flag\FlagValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\DieselValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\EthanolValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\GnvValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\PremiumGasValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\RegularGasValueObject;
use API\CheckPrice\Domain\ValueObjects\GasStation\GasStationValueObject;

class FindGasStationListFromDocumentCase
{
    private FlagValidatorService $flagValidatorService;
    private AddressService $addressService;

    public function __construct(FlagValidatorService $flagValidatorService, AddressService $addressService)
    {
        $this->flagValidatorService = $flagValidatorService;
        $this->addressService = $addressService;
    }
    public function execute(string $pdfInText) : string
    {
        $pdfInText = explode("\n", $pdfInText);

        $gasStationList = $this->mapGasStation($pdfInText);

        return json_encode($gasStationList);
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

    private function mapGasStationData($gasStation): ?GasStationValueObject
    {
        $id = $gasStation[0] ?? null;
        $name = $gasStation[1] ?? null;
        $address = $this->getAdressData($gasStation[2]) ?? null;
        $flagData = $gasStation[3] ?? null;

        if (!$id || !$name || !$address || !$flagData) {
            return null;
        }

        $flagData = $gasStation[3] ?? null;
    
        if (!$id || !$name || !$address || !$flagData) {
            return null;
        }

        $flag = $this->flagValidatorService->validationForValidFlag($flagData);

        $gasData = $this->removeFlagFromGasData($flag, $gasStation[3]);

        return new GasStationValueObject(
            $id,
            $name,
            new AddressValueObject($address['street'], $address['number'], $address['neighborhood'], $address['city'], $address['state'], $address['zipCode']),
            new FlagValueObject($flag),
            new RegularGasValueObject($this->getGasPrice($gasData, 0)),
            new PremiumGasValueObject($this->getGasPrice($gasData, 1)),
            new DieselValueObject($this->getGasPrice($gasData, 2)),
            new EthanolValueObject($this->getGasPrice($gasData, 3)),
            new GnvValueObject($this->getGasPrice($gasData, 4))
        );
    }

    function getAdressData($address)
    {
        $street = $this->addressService->getStreetFromString($address);

        return [
            'street' => $street,
            'neighborhood' => $this->addressService->getNeighborhoodFromString($address),
            'number' => $this->addressService->getNumberFromString($address),
            'city' => 'Joinville',
            'state' => 'SC',
            'zipCode' => ''
        ];
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
        $start = false;
        $end = false;
        $gasStation = [];

        foreach ($arrGasStation as $gasStationData) {
            if (strpos($gasStationData, 'Pesquisa de Preços - Combustíveis') !== false) {
                $start = true;
            }

            if (strpos($gasStationData, 'ECONOMIA ABASTECENDO COM O MENOR PREÇO') !== false) {
                $end = true;
            }

            if ($start && !$end) {
                $gasStation[] = $gasStationData;
            }
        }

        return $gasStation;
    }

    private function removeFlagFromGasData($flag, $gasData) 
    {
        $gasData = substr($gasData, strlen($flag . ' '));

        return explode(' ', $gasData);
    }
}
