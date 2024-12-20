<?php

namespace API\CheckPrice\Infra\Government;

use API\CheckPrice\Domain\Generic\ValueObjects\Date\DateValueObject;
use API\CheckPrice\Domain\Government\Services\GovernmentAPIInterface;

class JoinvilleGovernmentBaseAPI implements GovernmentAPIInterface
{
    private DateValueObject $DateValueObject;

    public function __construct(DateValueObject $DateValueObject)
    {
        $this->DateValueObject = $DateValueObject;
    }

    public function getBaseUrl() : string
    {
        return 'https://www.joinville.sc.gov.br/wp-content/uploads/' . $this->DateValueObject->getYear() . '/' . $this->DateValueObject->getNumericMonth(true) . '/';
    }
    
}