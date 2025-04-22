<?php

namespace API\CheckPrice\Domain\GasStation\UseCases;

use API\CheckPrice\Domain\GasStation\Entities\GasStation;
use API\CheckPrice\Domain\GasStation\Repositories\GasStationRepositoryInterface;

class GetListOfPricesByZipCodeCase
{
    private $repository;

    public function __construct(GasStationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $zipCode): ?GasStation
    {
        return $this->repository->getByZipCode($zipCode);
    }
}