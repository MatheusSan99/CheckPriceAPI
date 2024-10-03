<?php

namespace API\CheckPrice\Domain\Service;

use Exception;

class FlagValidatorService
{
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
        throw new Exception('Inválid flag', 400);
    }
}
