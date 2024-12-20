<?php

namespace API\CheckPrice\Domain\GasStation\ValueObjects\Flag;

class FlagValueObject implements \JsonSerializable 
{
    private $flagName;

    public function __construct(string $flagName)
    {
        $this->setFlagName($flagName);
    }

    public function getFlagName(): string
    {
        return $this->flagName;
    }

    public function jsonSerialize() : array
    {
        return [
            'flagName' => $this->getFlagName()
        ];
    }

    private function setFlagName(string $flagInString) 
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
                $this->flagName = $flag;
                return;
            }
        }
        throw new \Exception('Flag not found', 400);
    }

}