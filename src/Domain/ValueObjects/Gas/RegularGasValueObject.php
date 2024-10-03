<?php

namespace API\CheckPrice\Domain\ValueObjects\Gas;

use JsonSerializable;

class RegularGasValueObject implements Gas, JsonSerializable
{
    use PriceValidator;
    use JsonSerializeGas;

    private string $type = 'Gasolina Comum';

    public function __construct(float $price)
    {
        $this->setPrice($price);
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getType(): string
    {
        return $this->type;
    }
}