<?php

namespace API\CheckPrice\Infrastructure\Services;

use API\CheckPrice\Domain\ValueObjects\Address\CepValueObject;
use API\CheckPrice\Infra\Connection\CurlConnection;

class ViaCepService
{
    private CurlConnection $curlConnection;

    public function __construct(CurlConnection $curlConnection)
    {
        $this->curlConnection = $curlConnection;
    }

    public function getAddressByCep(CepValueObject $cep): array
    {
        $url = "https://viacep.com.br/ws/{$cep->getCep()}/json/";
        $response = $this->curlConnection->getResponse($url);

        if (isset($response['erro'])) {
            throw new \Exception('CEP inválido');
        }
        return $response;
    }

    public function getCepFromAddress(string $state, string $city, string $street): string
    {
        $formattedStreet = $this->formatStreetForRequest($street);
        $url = "https://viacep.com.br/ws/{$state}/{$city}/{$formattedStreet}/json/";

        $response = $this->curlConnection->getResponse($url);

        if (isset($response['erro'])) {
            return 'CEP não encontrado';
        }

        return $response['cep'];
    }

    private function formatStreetForRequest(string $street): string
    {
        return str_replace(['R.', 'AV.', ' '], ['', '', '+'], strtoupper($street));
    }
}
