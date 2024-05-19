<?php

namespace API\CheckPrice\Domain\Entity\GasStation\UseCase;

use API\CheckPrice\Domain\Entity\GasStation\FlagEntity;

class FlagCase
{
    private FlagEntity $flag;

    public function getFlag(): FlagEntity
    {
        return $this->flag;
    }

    public function setFlag(FlagEntity $flag): void
    {
        $this->flag = $flag;
    }

    public function validationForValidFlag(string $flagInString) 
    {
        $allowedFlags = [
            'REDE DIAS',
            'PETROSOL',
            'IPIRANGA',
            'BR',
            'SHELL',
            'RODOIL',
            'ALE',
            'STANG',
            'RAIZEN',
            'TEXACO',
            'BIG',
            'POSTO BR',
            'PETROBRAS',
            'SINCLAIR',
            'PETROBRAS DISTRIBUIDORA',
            'PETROBRAS DISTRIBUIDORA S/A',
            'PETROLEUM',
            'BRANCA',
            'RC POSTOS',
            'RDP',
            'POTENCIAL',
            'DIBRAPE'
        ];
    
        foreach ($allowedFlags as $flag) {
            if (stripos($flagInString, $flag) !== false) {
                return $flag;
            }
        }
        return 'Bandeira Não Encontrada';
    }
}