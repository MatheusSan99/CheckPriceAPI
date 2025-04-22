<?php

namespace API\CheckPrice\Domain\GasStation\UseCases;

use API\CheckPrice\Domain\GasStation\Repositories\GasStationRepositoryInterface;

class GetListOfPricesCase
{
    private $repository;

    public function __construct(GasStationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute($limit, $offset): array
    {
        return $this->repository->getAll($limit, $offset);
    }
}