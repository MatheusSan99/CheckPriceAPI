<?php

namespace API\CheckPrice\Application\ParamsValidation\DateValidation;

final class MonthValidation 
{
    public static function validate(int $month) 
    {
        if ($month < 1 || $month > 12) {
            throw new \Exception('Please provide a valid month between 1 and 12.', 400);
        }
        return $month;
    }
}