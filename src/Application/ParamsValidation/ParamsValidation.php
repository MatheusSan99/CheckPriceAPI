<?php

namespace API\CheckPrice\Application\ParamsValidation;

final class ParamsValidation 
{
    public function validateMonth($month) 
    {
        if (!is_numeric($month)) {
            throw new \Exception('Please provide a valid numeric month.', 400);
        }

        if ($month < 1 || $month > 12) {
            throw new \Exception('Please provide a valid month between 1 and 12.', 400);
        }
    }

    public function validateYear($year) 
    {
        if (!is_numeric($year)) {
            throw new \Exception('Please provide a valid numeric year.', 400);
        }

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
    }
}