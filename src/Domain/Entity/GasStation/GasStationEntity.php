<?php

namespace API\CheckPrice\Domain\Entity\GasStation;

use API\CheckPrice\Domain\Entity\Adress\AdressEntity;
use API\CheckPrice\Domain\Entity\Gas\DieselEntity;
use API\CheckPrice\Domain\Entity\Gas\EthanolEntity;
use API\CheckPrice\Domain\Entity\Gas\GnvEntity;
use API\CheckPrice\Domain\Entity\Gas\PremiumGasEntity;
use API\CheckPrice\Domain\Entity\Gas\RegularGasEntity;

class GasStationEntity implements \JsonSerializable 
{
    private $id;
    private $name;
    private AdressEntity $address;
    private FlagEntity $flag;
    private RegularGasEntity $regularGas;
    private PremiumGasEntity $premiumGas;
    private DieselEntity $diesel;
    private EthanolEntity $ethanol;
    private GnvEntity $gnv;

    public function __construct(
        int $id, 
        string $name, 
        AdressEntity $address, 
        FlagEntity $flag, 
        RegularGasEntity $regularGas, 
        PremiumGasEntity $premiumGas, 
        DieselEntity $diesel, 
        EthanolEntity $ethanol, 
        GnvEntity $gnv
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

    public function getAddress(): AdressEntity
    {
        return $this->address;
    }

    public function getFlag(): FlagEntity
    {
        return $this->flag;
    }

    public function getRegularGas(): RegularGasEntity
    {
        return $this->regularGas;
    }

    public function getPremiumGas(): PremiumGasEntity
    {
        return $this->premiumGas;
    }

    public function getDiesel(): DieselEntity
    {
        return $this->diesel;
    }

    public function getEthanol(): EthanolEntity
    {
        return $this->ethanol;
    }

    public function getGnv(): GnvEntity
    {
        return $this->gnv;
    }

    public function jsonSerialize() : array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
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