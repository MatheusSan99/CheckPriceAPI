<?php

namespace API\CheckPrice\Domain\Entity\GasStation;


class FlagEntity implements \JsonSerializable 
{
    private $flagName;

    public function __construct(string $flagName)
    {
        $this->flagName = $flagName;
    }

    public function getFlagName(): string
    {
        return $this->flagName;
    }

    public function jsonSerialize() : array
    {
        return [
            'flagName' => $this->getFlagName()
        ];
    }


}