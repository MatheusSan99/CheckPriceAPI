<?php

namespace API\CheckPrice\Domain\DataSource\Services\UseCases;

use API\CheckPrice\Domain\Government\Services\GovernmentAPIInterface;

class SearchPriceCase
{
    private GovernmentAPIInterface $GovernmentAPIInterface;

    public function __construct(GovernmentAPIInterface $GovernmentAPIInterface)
    {
        $this->GovernmentAPIInterface = $GovernmentAPIInterface;
    }
    
    public function execute() 
    {
        return $this->GovernmentAPIInterface->getBaseUrl();
    }
}
