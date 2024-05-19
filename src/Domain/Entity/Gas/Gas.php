<?php

namespace API\CheckPrice\Domain\Entity\Gas;

interface Gas
{
    public function setPrice(float $price): void;
    public function getPrice(): float;
    public function getType(): string;
    public function jsonSerialize(Gas $gas): array;

}