<?php

namespace API\CheckPrice\Domain\ValueObjects\TypeSearchPrice;

use API\CheckPrice\Domain\ValueObjects\Date\DateValueObject;

class KitchenGasTypeValueObject implements TypeSearchInterface
{
    private DateValueObject $DateValueObject;

    public function __construct(DateValueObject $DateValueObject)
    {
        $this->DateValueObject = $DateValueObject;
    }

    public function getUrl() : string
    {
        return "Pesquisa-Precos-Gas-de-Cozinha-{$this->DateValueObject->getMonth()}{$this->DateValueObject->getYear()}.pdf";
    }
}