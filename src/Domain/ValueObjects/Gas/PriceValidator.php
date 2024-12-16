<?php

namespace API\CheckPrice\Domain\ValueObjects\Gas;

trait PriceValidator
{
    private float $price;

    public function setPrice(float $price): void
    {
        if ($price < 0) {
            throw new \InvalidArgumentException("O preço não pode ser negativo.");
        }

        $this->price = $price;
    }

    public function getPrice(): ?float
    {
        if (empty($this->price)) {
            return 'Preço não informado.';
        }

        return $this->price;
    }
}
