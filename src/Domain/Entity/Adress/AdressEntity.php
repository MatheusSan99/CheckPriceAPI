<?php

namespace API\CheckPrice\Domain\Entity\Adress;

use JsonSerializable;

class AdressEntity implements JsonSerializable 
{
    private string $street;
    private string $number;
    private string $neighborhood;
    private string $city;
    private string $state;
    private ?string $zipCode;

    public function __construct(string $street, string $number = null, string $neighborhood = null, string $city = null, string $state = null, ?string $zipCode = null)
    {
        $this->street = $street;
        $this->number = $number;
        $this->neighborhood = $neighborhood;
        $this->city = $city;
        $this->state = $state;
        $this->zipCode = $zipCode;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getNeighborhood(): string
    {
        return $this->neighborhood;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function jsonSerialize() : array
    {
        return [
            'street' => trim($this->getStreet()),
            'number' => trim($this->getNumber()),
            'neighborhood' => $this->getNeighborhood(),
            'city' => $this->getCity(),
            'state' => $this->getState(),
            'zipCode' => $this->getZipCode()
        ];
    }

    
}