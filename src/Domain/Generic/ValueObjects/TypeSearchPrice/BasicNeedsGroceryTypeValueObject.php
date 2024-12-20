<?php

namespace API\CheckPrice\Domain\Generic\ValueObjects\TypeSearchPrice;

use API\CheckPrice\Domain\Generic\ValueObjects\Date\DateValueObject;

class BasicNeedsGroceryTypeValueObject implements TypeSearchInterface
{
    private DateValueObject $DateValueObject;

    public function __construct(DateValueObject $DateValueObject)
    {
        $this->DateValueObject = $DateValueObject;
    }

    public function getUrl() : string
    {
        return "Pesquisa-de-Precos-Cesta-Basica-{$this->DateValueObject->getMonth()}{$this->DateValueObject->getYear()}.pdf";
    }
}