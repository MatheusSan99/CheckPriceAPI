<?php

namespace API\CheckPrice\Domain\ValueObjects\Gas;

interface Gas
{
    public function setPrice(float $price): void;
    public function getPrice(): float;
    public function getType(): string;
    public function jsonSerialize(): array;

}