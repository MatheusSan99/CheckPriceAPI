<?php

namespace API\CheckPrice\Domain\Entity\Gas;

use JsonSerializable;

class PremiumGasEntity implements Gas, JsonSerializable
{
    use PriceValidator;
    use JsonSerializeGas;

    private string $type = 'Gasolina Aditivada';

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