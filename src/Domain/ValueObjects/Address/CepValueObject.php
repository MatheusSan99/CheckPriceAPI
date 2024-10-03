<?php

namespace API\CheckPrice\Domain\ValueObjects\Address;

class CepValueObject
{
    private string $cep;

    public function __construct(string $cep)
    {
        if (!$this->isValidCep($cep)) {
            throw new \InvalidArgumentException('CEP inválido');
        }
        $this->cep = $cep;
    }

    public function getCep(): string
    {
        return $this->cep;
    }

    private function isValidCep(string $cep): bool
    {
        return preg_match('/^[0-9]{5}-?[0-9]{3}$/', $cep);
    }
}
