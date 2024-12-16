<?php

namespace API\CheckPrice\Domain\ValueObjects\GasStation;

use API\CheckPrice\Domain\ValueObjects\Gas\DieselValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\EthanolValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\GnvValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\PremiumGasValueObject;
use API\CheckPrice\Domain\ValueObjects\Gas\RegularGasValueObject;
use API\CheckPrice\Domain\ValueObjects\Address\AddressValueObject;
use API\CheckPrice\Domain\ValueObjects\Flag\FlagValueObject;

class GasStationValueObject implements \JsonSerializable 
{
    private $id;
    private $name;
    private AddressValueObject $address;
    private FlagValueObject $flag;
    private RegularGasValueObject $regularGas;
    private PremiumGasValueObject $premiumGas;
    private DieselValueObject $diesel;
    private EthanolValueObject $ethanol;
    private GnvValueObject $gnv;

    public function __construct(
        int $id, 
        string $name, 
        AddressValueObject $address, 
        FlagValueObject $flag, 
        RegularGasValueObject $regularGas, 
        PremiumGasValueObject $premiumGas, 
        DieselValueObject $diesel, 
        EthanolValueObject $ethanol, 
        GnvValueObject $gnv
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->address = $address;
        $this->flag = $flag;
        $this->regularGas = $regularGas;
        $this->premiumGas = $premiumGas;
        $this->diesel = $diesel;
        $this->ethanol = $ethanol;
        $this->gnv = $gnv;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): AddressValueObject
    {
        return $this->address;
    }

    public function getFlag(): FlagValueObject
    {
        return $this->flag;
    }

    public function getRegularGas(): RegularGasValueObject
    {
        return $this->regularGas;
    }

    public function getPremiumGas(): PremiumGasValueObject
    {
        return $this->premiumGas;
    }

    public function getDiesel(): DieselValueObject
    {
        return $this->diesel;
    }

    public function getEthanol(): EthanolValueObject
    {
        return $this->ethanol;
    }

    public function getGnv(): GnvValueObject
    {
        return $this->gnv;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->getId(),
            'name' => trim($this->getName()),
            'address' => $this->getAddress(),
            'flag' => $this->getFlag(),
            'regularGas' => $this->getRegularGas(),
            'premiumGas' => $this->getPremiumGas(),
            'diesel' => $this->getDiesel(),
            'ethanol' => $this->getEthanol(),
            'gnv' => $this->getGnv()
        ];
    }


}