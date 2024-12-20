<?php

namespace API\CheckPrice\Domain\GasStation\ValueObjects\Gas;

use JsonSerializable;

class EthanolValueObject implements GasInterface, JsonSerializable
{
    use PriceValidator;
    use JsonSerializeGas;

    private string $type = 'Etanol';

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