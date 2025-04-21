<?php

namespace API\CheckPrice\Domain\GasStation\Repositories;

use API\CheckPrice\Domain\GasStation\Entities\GasStation as GasStationEntity;

interface GasStationRepositoryInterface
{
    public function getAll(): array;

    public function getByCNPJ(string $cnpj): ?GasStationEntity;

    public function getByAddress(string $address): ?GasStationEntity;

    public function getByCompanyName(string $companyName): ?GasStationEntity;

    public function getByFantasyName(string $fantasyName): ?GasStationEntity;

    public function getByNeighborhood(string $neighborhood): ?GasStationEntity;

    public function getByCity(string $city): ?GasStationEntity;

    public function getByState(string $state): ?GasStationEntity;

    public function getByZipCode(string $zipCode): ?GasStationEntity;

    public function getByNeighborhoodAndCity(string $neighborhood, string $city): ?GasStationEntity;
    
    public function updatePrice(string $cnpj, string $address, string $product, float $price): bool;
}