<?php

namespace API\CheckPrice\Services;

use Exception;

trait AddressHandler
{
    public function getAddressByCep(string $cep): array
    {
        $url = "https://viacep.com.br/ws/{$cep}/json/";
        $response = file_get_contents($url);
        $address = json_decode($response, true);

        if (isset($address['erro'])) {
            throw new Exception('CEP inválido');
        }

        return $address;
    }

    public function getNeighborhoodFromString(string $completeAddress): string
    {
        $neighborhoods = [
            'Adhemar Garcia',
            'América',
            'Anita Garibaldi',
            'Atiradores',
            'Aventureiro',
            'Boa Vista',
            'Boehmerwald',
            'Bom Retiro',
            'Bucarein',
            'Centro',
            'Comasa',
            'Costa e Silva',
            'Dona Francisca',
            'Espinheiros',
            'Fátima',
            'Floresta',
            'Glória',
            'Guanabara',
            'Iririú',
            'Itaum',
            'Itinga',
            'Jardim Iririú',
            'Jardim Paraíso',
            'Jardim Sofia',
            'Jarivatuba',
            'João Costa',
            'Morro do Meio',
            'Nova Brasília',
            'Paranaguamirim',
            'Parque Guarani',
            'Petrópolis',
            'Pirabeiraba',
            'Profipo',
            'Rio Bonito',
            'Saguaçu',
            'Santa Catarina',
            'Santo Antônio',
            'São Marcos',
            'Ulysses Guimarães',
            'Vila Cubatão',
            'Vila Nova',
            'Zona Industrial Norte',
            'Zona Industrial Tupy'
        ];

        if (preg_grep("/{$completeAddress}/i", $neighborhoods)) {
            return $completeAddress;
        }

        return 'Bairro Não Informado';
    }

    public function getStreetFromString(string $completeAddress) {
        return substr($completeAddress, 0, strpos($completeAddress, ','));
    }

    public function getNumberFromString(string $completeAddress) {
        $number = substr($completeAddress, strpos($completeAddress, ',') + 1);
        $number = substr($number, 0, strpos($number, ','));
        $number = str_replace('s/n', 'Numero Não Encontrado', $number);
        return $number;
    }

}
