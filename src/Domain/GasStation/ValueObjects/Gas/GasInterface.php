<?php

namespace API\CheckPrice\Domain\GasStation\ValueObjects\Gas;

interface GasInterface
{
    public function setPrice(float $price): void;
    public function getPrice(): float;
    public function getType(): string;
    public function jsonSerialize(): array;

}