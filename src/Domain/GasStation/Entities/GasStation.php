<?php

namespace API\CheckPrice\Domain\GasStation\Entities;

use API\CheckPrice\Domain\Generic\ValueObjects\CNPJ\CNPJValueObject;
use API\CheckPrice\Domain\Generic\ValueObjects\Address\AddressValueObject;

class GasStation implements \JsonSerializable
{
    private CNPJValueObject $cnpj;
    private string $companyName;
    private ?string $fantasyName;
    private AddressValueObject $address;
    private string $flag;
    private string $product;
    private string $unitOfMeasure;
    private float $price;
    private string $collectionDate;

    public function __construct(
        CNPJValueObject $cnpj,
        string $companyName,
        ?string $fantasyName,
        AddressValueObject $address,
        string $flag,
        string $product,
        string $unitOfMeasure,
        float $price,
        string $collectionDate
    ) {
        $this->cnpj = $cnpj;
        $this->companyName = $companyName;
        $this->fantasyName = $fantasyName;
        $this->address = $address;
        $this->flag = $flag;
        $this->product = $product;
        $this->unitOfMeasure = $unitOfMeasure;
        $this->price = $price;
        $this->collectionDate = $collectionDate;
    }

    public function getCNPJ(): CNPJValueObject
    {
        return $this->cnpj;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getFantasyName(): ?string
    {
        return $this->fantasyName;
    }

    public function getAddress(): AddressValueObject
    {
        return $this->address;
    }

    public function getFlag(): string
    {
        return $this->flag;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

    public function getUnitOfMeasure(): string
    {
        return $this->unitOfMeasure;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCollectionDate(): string
    {
        return $this->collectionDate;
    }

    public function jsonSerialize(): array
    {
        return [
            'cnpj' => $this->cnpj->getCNPJ(),
            'companyName' => $this->companyName,
            'fantasyName' => $this->fantasyName,
            'address' => $this->address->jsonSerialize(),
            'flag' => $this->flag,
            'product' => $this->product,
            'unitOfMeasure' => $this->unitOfMeasure,
            'price' => $this->price,
            'collectionDate' => $this->collectionDate,
        ];
    }
}
    