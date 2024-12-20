<?php

namespace API\CheckPrice\Domain\GasStation\ValueObjects\Gas;

trait JsonSerializeGas
{
    public function jsonSerialize(): array
    {
        $price = !empty($this->getPrice()) ? 'R$: ' . $this->getPrice() : 'Preço não informado';
        
        return [
            'type' => $this->getType(),
            'price' => $price
        ];
    }
}
