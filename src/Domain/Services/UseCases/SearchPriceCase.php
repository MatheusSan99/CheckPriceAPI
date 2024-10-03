<?php

namespace API\CheckPrice\Domain\UseCases;

use API\CheckPrice\Domain\Services\Government\GovernmentAPIInterface;
use API\CheckPrice\Domain\ValueObjects\Date\DateValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\TypeSearchInterface;

class SearchPriceCase
{
    private GovernmentAPIInterface $GovernmentAPIInterface;
    private TypeSearchInterface $TypeSearchInterface;

    public function __construct(GovernmentAPIInterface $GovernmentAPIInterface, TypeSearchInterface $TypeSearchInterface)
    {
        $this->GovernmentAPIInterface = $GovernmentAPIInterface;
        $this->TypeSearchInterface = $TypeSearchInterface;
    }
    
    public function execute() 
    {
        $this->GovernmentAPIInterface->getBaseUrl() . $this->TypeSearchInterface->getUrl();
    }
}
