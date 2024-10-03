<?php

namespace API\CheckPrice\Infra\Government;

use API\CheckPrice\Domain\Services\Government\GovernmentAPIInterface;
use API\CheckPrice\Domain\ValueObjects\Date\DateValueObject;

class JoinvilleGovernmentBaseAPI implements GovernmentAPIInterface
{
    private DateValueObject $DateValueObject;

    public function __construct(DateValueObject $DateValueObject)
    {
        $this->DateValueObject = $DateValueObject;
    }

    public function getBaseUrl() : string
    {
        return 'https://www.joinville.sc.gov.br/wp-content/uploads/' . $this->DateValueObject->getYear() . '/0' . $this->DateValueObject->getNumericMonth() . '/';
    }
    
}