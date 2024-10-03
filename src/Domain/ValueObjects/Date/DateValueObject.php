<?php

namespace API\CheckPrice\Domain\ValueObjects\Date;

use DateTime;
use DateTimeZone;

class DateValueObject
{
    private $DateTime;
    private $month;
    private $numericMonth;
    private $year;

    public function __construct(int $month, int $year)
    {
        $this->DateTime = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

        if (!is_numeric($month)) {
            $this->numericMonth = $this->DateTime->format('m');
            $this->year = $this->DateTime->format('Y');
            $this->mapMonth($this->numericMonth);
        }

        if (is_numeric($month) && is_numeric($year)) {
            $this->numericMonth = $month;
            $this->year = $year;
            $this->mapMonth($month);
        }
    }

    private function mapMonth($month) {
        $months = [
            '1' => 'janeiro',
            '2' => 'fevereiro',
            '3' => 'marco',
            '4' => 'abril',
            '5' => 'maio',
            '6' => 'junho',
            '7' => 'julho',
            '8' => 'agosto',
            '9' => 'setembro',
            '10' => 'outubro',
            '11' => 'novembro',
            '12' => 'dezembro'
        ];

        $this->month = $months[$month];
    }

    public function setPreviousMonth() {
        $previousMonth = $this->DateTime->modify('-1 month');
        $this->numericMonth = $previousMonth->format('m');
        $this->year = $previousMonth->format('Y');
        $this->month = $this->mapMonth($this->numericMonth);
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function getNumericMonth()
    {
        return $this->numericMonth;
    }

    public function getYear()
    {
        return $this->year;
    }

}
