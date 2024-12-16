<?php

namespace API\CheckPrice\Domain\Factories;

use API\CheckPrice\Domain\ValueObjects\Date\DateValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\BarbecueTypeValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\BasicNeedsGroceryTypeValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\FishTypeValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\GasTypeValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\KitchenGasTypeValueObject;
use API\CheckPrice\Domain\ValueObjects\TypeSearchPrice\TypeSearchInterface;

class TypeSearchFactory
{
    public static function create(string $type, DateValueObject $DateValueObject) : TypeSearchInterface
    {
        switch ($type) {
            case 'churrasco':
                return new BarbecueTypeValueObject($DateValueObject);
            case 'combustiveis':
                return new GasTypeValueObject($DateValueObject);
            case 'cestaBasica':
                return new BasicNeedsGroceryTypeValueObject($DateValueObject);
            case 'pescado':
                return new FishTypeValueObject($DateValueObject);
            case 'gasCozinha':
                return new KitchenGasTypeValueObject($DateValueObject);
            default:
                throw new \InvalidArgumentException('Invalid type provided', 400);
        }
    }
}