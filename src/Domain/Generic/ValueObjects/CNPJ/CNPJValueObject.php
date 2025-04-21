<?php

namespace API\CheckPrice\Domain\Generic\ValueObjects\CNPJ;

class CNPJValueObject
{
    private string $cnpj;

    public function __construct(string $cnpj)
    {
        $this->cnpj = $this->validateCNPJ($cnpj);
    }

    public function getCNPJ(): string
    {
        return $this->cnpj;
    }

    private function validateCNPJ(string $cnpj): string
    {
        $cnpj = preg_replace('/\D/', '', $cnpj);

        if (strlen($cnpj) !== 14) {
            throw new \InvalidArgumentException('CNPJ must be 14 digits long.');
        }

        if (!preg_match('/^\d{14}$/', $cnpj)) {
            throw new \InvalidArgumentException('Invalid CNPJ format.');
        }

        return $cnpj;
    }
}