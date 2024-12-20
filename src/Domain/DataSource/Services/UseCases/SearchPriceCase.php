<?php

namespace API\CheckPrice\Domain\DataSource\Services\UseCases;

use API\CheckPrice\Domain\Generic\ValueObjects\TypeSearchPrice\TypeSearchInterface;
use API\CheckPrice\Domain\Government\Services\GovernmentAPIInterface;

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
        return $this->GovernmentAPIInterface->getBaseUrl() . $this->TypeSearchInterface->getUrl();
    }
}
