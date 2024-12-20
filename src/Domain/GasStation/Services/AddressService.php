<?php

namespace API\CheckPrice\Domain\Services;

final class AddressService
{

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

        foreach ($neighborhoods as $neighborhood) {
            if (stripos($completeAddress, $neighborhood) !== false) {
                return $neighborhood;
            }
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

    public function formatStreetForRequisition($street) 
    {
        $street = strtoupper($street);
        $street = str_replace('R.', '', $street);
        $street = str_replace('DR.', 'Doutor', $street);
        $street = str_replace('AV.', '', $street);
        $street = str_replace('ROD.', '', $street);
        $street = str_replace('BR.', '', $street);
        $street = str_replace('EST.', '', $street);
        $street = str_replace('AL.', '', $street);
        $street = str_replace('TR.', '', $street);
        $street = str_replace('V.', '', $street);
        $street = str_replace('PÇA.', '', $street);

        $street = str_replace(' ', '+', $street);

        return $street;
    }

}
