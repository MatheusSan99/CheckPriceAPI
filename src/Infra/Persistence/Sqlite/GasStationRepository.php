<?php

namespace API\CheckPrice\Infra\Persistence\Sqlite;

use API\CheckPrice\Domain\GasStation\Entities\GasStation;
use API\CheckPrice\Domain\GasStation\Repositories\GasStationRepositoryInterface;
use API\CheckPrice\Domain\Generic\ValueObjects\Address\AddressValueObject;
use API\CheckPrice\Domain\Generic\ValueObjects\CNPJ\CNPJValueObject;

class GasStationRepository implements GasStationRepositoryInterface
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(int $limit, int $offset): array
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis LIMIT ? OFFSET ?');
        $stmt->execute([$limit, $offset]);
    
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    
        return array_map(fn($item) => $this->mapToEntity($item), $results);
    }
    

    public function getByCNPJ(string $cnpj): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE CNPJ = :cnpj');
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByAddress(string $address): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE ENDERECO = :address');
        $stmt->bindParam(':address', $address);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByCompanyName(string $companyName): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE RAZAO = :company_name');
        $stmt->bindParam(':company_name', $companyName);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByFantasyName(string $fantasyName): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE FANTASIA = :fantasy_name');
        $stmt->bindParam(':fantasy_name', $fantasyName);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByNeighborhood(string $neighborhood): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE BAIRRO = :neighborhood');
        $stmt->bindParam(':neighborhood', $neighborhood);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByCity(string $city): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE MUNICIPIO = :city');
        $stmt->bindParam(':city', $city);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByState(string $state): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE ESTADO = :state');
        $stmt->bindParam(':state', $state);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByZipCode(string $zipCode): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE CEP = :zip_code');
        $stmt->bindParam(':zip_code', $zipCode);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function getByNeighborhoodAndCity(string $neighborhood, string $city): ?GasStation
    {
        $stmt = $this->connection->prepare('SELECT * FROM combustiveis WHERE BAIRRO = :neighborhood AND MUNICIPIO = :city');
        $stmt->bindParam(':neighborhood', $neighborhood);
        $stmt->bindParam(':city', $city);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result ? $this->mapToEntity($result) : null;
    }

    public function updatePrice(string $cnpj, string $address, string $product, float $price): bool
    {
        $stmt = $this->connection->prepare(
            'UPDATE combustiveis SET PRECO_REVENDA = :price WHERE CNPJ = :cnpj AND ENDERECO = :address AND PRODUTO = :product'
        );
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':cnpj', $cnpj);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':product', $product);

        return $stmt->execute();
    }

    private function mapToEntity(array $item): GasStation
    {
        $CNPJValueObject = new CNPJValueObject($item['CNPJ']);
        $AddressValueObject = new AddressValueObject(
            $item['ENDERECO'],
            $item['NUMERO'],
            $item['BAIRRO'],
            $item['MUNICIPIO'],
            $item['ESTADO'],
            $item['CEP']
        );

        return new GasStation(
            $CNPJValueObject,
            $item['RAZAO'],
            $item['FANTASIA'],
            $AddressValueObject,
            $item['BANDEIRA'],
            $item['PRODUTO'],
            $item['UNIDADE_MEDIDA'],
            (float)$item['PRECO_REVENDA'],
            $item['DATA_COLETA']
        );
    }
}