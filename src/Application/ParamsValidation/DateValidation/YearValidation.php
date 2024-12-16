<?php

namespace API\CheckPrice\Application\ParamsValidation\DateValidation;

final class YearValidation 
{
    public static function validate(int $year) 
    {

        if (strlen($year) !== 4) {
            throw new \Exception('Please provide a valid year with 4 digits.', 400);
        }

        if ($year > date('Y')) {
            throw new \Exception('Please provide a valid year.', 400);
        }

        if ($year < date('Y') - 1) {
            $allowedYears = [
                date('Y'),
                date('Y') - 1,
            ];

            throw new \Exception('Year is too old, allowed year is: ' . implode(', ', $allowedYears) , 400);
        }
        return $year;
    }
}