<?php

namespace API\CheckPrice\Application\ParamsValidation\DateValidation;

final class DayValidation 
{
    public static function validate(int $day) 
    {
        if ($day > 31 || $day < 1) {
            throw new \Exception('Please provide a valid day between 1 and 31.', 400);
        }

        return $day;
    }
}