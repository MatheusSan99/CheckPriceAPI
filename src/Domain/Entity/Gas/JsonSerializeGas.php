<?php

namespace API\CheckPrice\Domain\Entity\Gas;

trait JsonSerializeGas
{
    public function jsonSerialize(Gas $gas): array
    {
        return [
            'type' => $gas->getType(),
            'price' => $gas->getPrice()
        ];
    }
}
